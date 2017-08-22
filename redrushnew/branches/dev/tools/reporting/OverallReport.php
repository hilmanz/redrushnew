<?php
/**
 * bot for searching facebook posts related to keywords
 */
include_once "bootstraps.php";
//include_once "libs/logger.php";
include_once "UserReport.php";
error_reporting(1);

class OverallReport {
    protected $log;
    protected $conn;
    protected $date;
    protected $date_ts;
    private $verbose = false;
    protected static $minigames =
    array("1"=>"SOLVE THE RUSH",     "2"=>"WHAT'S COOKING",
                    	  "3"=>"RUSH HOUR DELIVERY", "4"=>"SHOP TREASURE");

    public function OverallReport($conn, $log) {
        $this->conn = $conn;
        $this->log = $log;
    }

    public function setVerbose($verbose = false) {
        $this->verbose = $verbose;
    }

    public function date($date = null) {
        if (is_null($date)) {
            return $this->date;
        } else {
            $this->date = $date;
        }
    }

    public function date_ts($date_ts = null) {
        if (is_null($date_ts)) {
            return $this->date_ts;
        } else {
            $this->date_ts = $date_ts;
        }
    }

    public function process($date, $date_ts) {
        $this->date = $date;
        $this->date_ts = $date_ts;

        $last_user_id = $ret[0];
        $user_count = $this->getUserCount();
        $login_count = $this->getOverallLoginCount();
        $total_login_time = $this->getOverallLoginTime();
        $avg_time_per_login = $total_login_time * 1.0/$login_count;
        $total_page_view = $this->getOverallPageviewCount();
        $page_view_per_login = $total_page_view * 1.0/$login_count;
        $redeem_count = $this->getOverallRedeemCount();
        $activity_arr = $this->getOverallActivityHistory();
        $total_point = $this->getOverallPoint();
        $race_freq = $this->getOverallRaceCount();
        $modification_count = $this->getOverallCarModificationCount();
        $minigame_freq = $this->getOverallMinigamePlayCount();
        $participant_count = $this->getOverallUserParticipant();
        //var_dump($activity_arr);
        if ($this->verbose) {
            print $this->user_id . "::" . $login_count . "->" . $total_login_time . "->" .
            $avg_time_per_login . "->" . $total_page_view . "->" . $page_view_per_login . "->" .
            $redeem_count . "->" . $total_point . "\n\n";
        }
        $this->insertOverallReport($user_count, $page_view_per_login, $avg_time_per_login, $redeem_count,
        $total_page_view, $total_login_time, $login_count, $race_freq, $modification_count, $participant_count, $minigame_freq);
        $this->insertUserPerLevel();
        $this->insertOverallPageView();
        $this->insertOverallLoginHistory();
        $this->insertOverallActivityDistribution();
        $this->insertOverallMinigame($minigame_freq);
        $this->insertOverallLocation();
        $this->insertOverallGender();
        $this->insertOverallRedeem();
        $this->updateActivityDistribution();
    }

    public function insertUserPerLevel($level_num = 10) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        
        $query = "REPLACE ".$SCHEMA_REPORT.".rp_user_level_daily(date_d, LEVEL, user_count)
			      SELECT '" . $this->date . "', LEVEL, COUNT(user_id) num 
			      FROM ".$SCHEMA_GAME.".racing_level GROUP BY LEVEL";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert to ".$SCHEMA_REPORT.".rp_user_level_daily",$rs);
        for($level=1;$level<=$level_num;$level++) {
            $avg_time = -1.0;
            $query = "SELECT AVG(total_time) avg_time, SUM(total_time) total_time
                      FROM ".$SCHEMA_REPORT.".rp_user_race_level_data
                      WHERE date_d = '" . $this->date . "' AND level = " . $level;
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                if (!is_null($row['avg_time'])) {
                    $avg_time = intval($row['avg_time']);
                    $total_time = intval($row['total_time']);
                }
            }
            mysql_free_result($rs);
            if ($avg_time >= 0) {
                $jam = intval($avg_time / 3600);
                $menit = intval(intval($avg_time % 3600) / 60);
                $detik = intval(intval($avg_time % 3600) % 60);

                $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_overall_level_daily
                          (date_d, LEVEL, user_count, avg_time,
                           total_time, jam, menit, detik)VALUES
                          ('".$this->date."', '".$level."', '0', '".$avg_time."',
                           '".$total_time."', '".$jam."', '".$menit."', '".$detik."')";
                //print $query . "\n";
                $rs = mysql_query($query, $this->conn);
                $this->log->status("insert to ".$SCHEMA_REPORT.".rp_overall_level_daily",$rs);
            }
        }
        $query = "UPDATE ".$SCHEMA_REPORT.".rp_overall_level_daily AS A
                      INNER JOIN ".$SCHEMA_REPORT.".rp_user_level_daily AS B 
                      ON (A.date_d = B.date_d) AND (A.level = B.level)
                      SET A.user_count = B.user_count
                      WHERE A.date_d = '".$this->date."'";
        //print $query . "\n";
        $rs = mysql_query($query, $this->conn);
        $this->log->status("update user_count in ".$SCHEMA_REPORT.".rp_overall_level_daily",$rs);
        return $rs;
    }

    public function getUserCount() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $user_count = 0;
        $query = "SELECT COUNT(id) user_count FROM ".$SCHEMA.".kana_member
        		WHERE DATE(register_date) <= '" . $this->date . "' LIMIT 1";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $user_count = intval($row['user_count']);
        }
        mysql_free_result($rs);
        return $user_count;
    }

    public function getOverallPageviewCount() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $page_view_count = 0;
        $query = "SELECT SUM(page_view_count) total_page_view FROM ".$SCHEMA_REPORT.".rp_user_daily
        		  WHERE date_d = '" . $this->date . "' LIMIT 1";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $page_view_count = intval($row['total_page_view']);
        }
        mysql_free_result($rs);
        return $page_view_count;
    }

    public function getOverallLoginCount($use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $login_count = 0;
        $query = "SELECT SUM(login_count) total_count FROM ".$SCHEMA_REPORT.".rp_user_daily
        		  WHERE date_d = '" . $this->date . "' LIMIT 1";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $login_count = intval($row['total_count']);
        }
        mysql_free_result($rs);

        if ($login_count == 0) {
            $query = "SELECT COUNT(id) total_count FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
                      WHERE action_id IN (1)";
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                $login_count = intval($row['total_count']);
            }
            mysql_free_result($rs);
        }
        return $login_count;
    }

    function getOverallLoginTime() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $login_time = 0;
        $query = "SELECT SUM(login_time) total_time FROM ".$SCHEMA_REPORT.".rp_user_daily
        		  WHERE date_d = '" . $this->date . "' LIMIT 1";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $login_time = intval($row['total_time']);
        }
        mysql_free_result($rs);
        return $login_time;
    }

    function getOverallActivityHistory($num = 10, $use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $login_count = 0;
        $query = "SELECT id, action_id, action_value FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
		          ORDER BY id DESC LIMIT " . $num;
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $activities_array = array();
        $idx = 0;
        while ($row = mysql_fetch_assoc($rs)) {
            $activities_array[$idx] = $row;
            $idx++;
        }
        mysql_free_result($rs);
        return $activities_array;
    }

    function getOverallRedeemCount() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $redeem_count = 0;
        $query = "SELECT SUM(redeem_count) total_redeem FROM ".$SCHEMA_REPORT.".rp_user_daily
        		  WHERE date_d = '" . $this->date . "' LIMIT 1";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            #print $row['user_id'] . "->" . $row['num'] . "\n";
            $redeem_count = $row['total_redeem'];
        }
        mysql_free_result($rs);

        if ($redeem_count == 0) {
            $query = "SELECT COUNT(id) total_redeem FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
                      WHERE action_id IN (6)";
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                $redeem_count = intval($row['total_redeem']);
            }
            mysql_free_result($rs);
        }
        return $redeem_count;
    }

    function getOverallPoint($use_timestamp = false) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $user_point = 0;
        $query = "SELECT SUM(POINT) points FROM ".$SCHEMA_REPORT_TEMP.".tbl_user_points";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            #print $row['user_id'] . "->" . $row['num'] . "\n";
            $user_point = intval($row['points']);
        }
        mysql_free_result($rs);
        return $user_point;
    }

    function insertOverallReport($user_count, $page_view_per_login, $time_per_login, $redeem_count,
    $page_view_count, $login_time, $login_count, $race_freq, $modification_count, $participant_count, $minigame_freq) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_overall_daily
				(date_d, user_count, page_view_per_login, 
				 time_per_login, redeem_count, page_view_count, 
				 login_time, login_count, race_played_num, car_modif_num,
				 participant_count, mini_game_played_num)VALUES 
				('" . $this->date . "','" . $user_count . "','" . $page_view_per_login . "',
				 '" . $time_per_login . "','" . $redeem_count . "','" . $page_view_count . "',
				 '" . $login_time . "','" . $login_count . "','" . $race_freq . "','" . $modification_count . "',
				 '" . $participant_count . "','".$minigame_freq."')";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert to ".$SCHEMA_REPORT.".rp_overall_daily",$rs);
        return $rs;
    }

    function insertOverallPageView($use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_overall_pageview_daily(date_d, action_id, title, pageview_count)
				  SELECT DATE(date_time), action_id, action_value, COUNT(id) FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
                  WHERE action_id IN (7) GROUP BY DATE(date_time), action_id, action_value";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert PAGE to ".$SCHEMA_REPORT.".rp_overall_pageview_daily",$rs);
        $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_overall_pageview_daily(date_d, action_id, title, pageview_count)
				  SELECT DATE(A.date_time), A.action_id, B.title, COUNT(A.id) 
				  FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log A
                  INNER JOIN ".$SCHEMA.".rr_news B ON A.action_value = B.id
        		  WHERE A.action_id IN (2) GROUP BY DATE(A.date_time), A.action_id, A.action_value";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert ARTICLE to ".$SCHEMA_REPORT.".rp_overall_pageview_daily",$rs);
        return $rs;
    }

    function insertOverallLoginHistory($use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_overall_login_history_daily
        		  (date_d, HOUR, day_of_week, login_count)
				  SELECT DATE(date_time), HOUR(date_time), DAYOFWEEK(date_time)-1, COUNT(id)
                  FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
                  WHERE action_id = 1 GROUP BY DATE(date_time), HOUR(date_time)";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert to ".$SCHEMA_REPORT.".rp_overall_login_history_daily",$rs);

        $peak_date = '0000-00-00';
        $peak_date_count = 0;
        $query = "SELECT date_d, SUM(login_count) num
				  FROM ".$SCHEMA_REPORT.".rp_overall_login_history_daily
				  GROUP BY date_d ORDER BY num DESC LIMIT 1";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            if (!is_null($row['date_d'])) {
                $peak_date = $row['date_d'];
                $peak_date_count = $row['num'];
            }
        }
        mysql_free_result($rs);
        if ($peak_date_count > 0) {
            $peak_hour = 0;
            $peak_hour_count = 0;
            $query = "SELECT HOUR, SUM(login_count) num
    				  FROM ".$SCHEMA_REPORT.".rp_overall_login_history_daily
    				  GROUP BY HOUR ORDER BY num DESC LIMIT 1";
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                if (!is_null($row['HOUR'])) {
                    $peak_hour = $row['HOUR'];
                    $peak_hour_count = $row['num'];
                }
            }
            mysql_free_result($rs);

            $peak_dow = 0;
            $peak_dow_count = 0;
            $query = "SELECT day_of_week, SUM(login_count) num
    				  FROM ".$SCHEMA_REPORT.".rp_overall_login_history_daily
    				  GROUP BY day_of_week ORDER BY num DESC LIMIT 1";
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                if (!is_null($row['day_of_week'])) {
                    $peak_dow = $row['day_of_week'];
                    $peak_dow_count = $row['num'];
                }
            }
            mysql_free_result($rs);

            $query = "TRUNCATE ".$SCHEMA_REPORT.".rp_overall_login_history";
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            $this->log->status("truncate ".$SCHEMA_REPORT.".rp_overall_login_history",$rs);
            $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_overall_login_history
					  (date, date_login_count, hour, 
					   hour_login_count, day_of_week, dow_login_count,last_update)VALUES
					  ('" . $peak_date . "','" . $peak_date_count . "','" . $peak_hour . "',
					   '" . $peak_hour_count . "','" . $peak_dow . "','" . $peak_dow_count . "',NOW())";

            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            $this->log->status("insert to ".$SCHEMA_REPORT.".rp_overall_login_history",$rs);
        }

        mysql_free_result($rs);
        return $rs;
    }

    function insertOverallActivityDistribution($use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $query = "SELECT id, activityName as name FROM ".$SCHEMA.".tbl_activity_actions";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $activity_array = array();
        while ($row = mysql_fetch_assoc($rs)) {
            array_push($activity_array, $row);
        }
        mysql_free_result($rs);

        //hitung total aktivitas
        $query = "SELECT COUNT(id) num FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log";
        $rs = mysql_query($query, $this->conn);
        $total_act_count = 0;
        if ($row = mysql_fetch_assoc($rs)) {
            $total_act_count = intval($row['num']);
        }
        mysql_free_result($rs);

        //masukkan nilai satu persatu
        foreach ($activity_array as $activity_tuple) {
            $act_count = 0;
            $act_id = intval($activity_tuple['id']);
            $act_name = mysql_real_escape_string($activity_tuple['name']);
            $query = "SELECT COUNT(id) num FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
                      WHERE action_id = " . $act_id;
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                $act_count = intval($row['num']);
            }
            mysql_free_result($rs);

            if ($act_count > 0) {
                if ($act_id == 7) {
                    $page_activities = array();
                    $query = "SELECT action_value, COUNT(id) num  
                              FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
                              WHERE action_id = 7
                              GROUP BY action_value ORDER BY num DESC LIMIT 5";
                    $rs = mysql_query($query, $this->conn);
                    while ($row = mysql_fetch_assoc($rs)) {
                        array_push($page_activities, $row);
                    }
                    mysql_free_result($rs);

                    foreach($page_activities as $page_activity) {
                        $page_act_name = mysql_escape_string("page:" . $page_activity['action_value']);
                        $page_act_count = intval($page_activity['num']);
                        $percentage = $page_act_count * 100.00 / $total_act_count;
                        $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_activity_dist_daily
                                  (date_d, activity_id, activity_name, num, total, percentage)VALUES
                                  ('".$this->date."','".$act_id."','".$page_act_name."','".$page_act_count."','".$total_act_count."','".$percentage."')";
                        $rs = mysql_query($query, $this->conn);
                        $this->log->status("insert act#".intval($activity_tuple['id']).":".$page_activity['action_value']." to ".$SCHEMA_REPORT.".rp_activity_dist_daily",$rs);
                        $act_count -= $page_act_count;
                    }

                    if ($act_count > 0) {
                        $page_act_name = mysql_escape_string("page:other");
                        $page_act_count = $act_count;
                        $percentage = $page_act_count * 100.00 / $total_act_count;
                        $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_activity_dist_daily
                                  (date_d, activity_id, activity_name, num, total, percentage)VALUES
                                  ('".$this->date."','".$act_id."','".$page_act_name."','".$page_act_count."','".$total_act_count."','".$percentage."')";
                        $rs = mysql_query($query, $this->conn);
                        $this->log->status("insert act#".intval($activity_tuple['id']).":".$page_activity['action_value']." to ".$SCHEMA_REPORT.".rp_activity_dist_daily",$rs);
                    }

                    unset($page_activities);

                } else {
                    $percentage = $act_count * 100.00 / $total_act_count;
                    $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_activity_dist_daily
                      (date_d, activity_id, activity_name, num, total, percentage)VALUES
                      ('".$this->date."','".$act_id."','".$act_name."','".$act_count."','".$total_act_count."','".$percentage."')";
                    $rs = mysql_query($query, $this->conn);
                    $this->log->status("insert act#".intval($activity_tuple['id'])." to ".$SCHEMA_REPORT.".rp_activity_dist_daily",$rs);
                }
            }
        }
    }

    function insertOverallMinigame($total_minigame_freq, $minigame_count = 4) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        for ($i = 0; $i < $minigame_count ; $i++) {
            $minigame_name = mysql_real_escape_string(self::$minigames["".($i+1)]);
            $game_freq = 0;
            $avg_total_time = 0;
            $query = "SELECT COUNT(id) game_freq, AVG(end_date_time-start_date_time) avg_total_time
                      FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_mini_game
                      WHERE game_id = " . ($i+1) . " AND n_status = 1 
                      GROUP BY game_id";
            //print $query . "\n";
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                $game_freq = intval($row['game_freq']);
                $avg_total_time = intval($row['avg_total_time']);
            }
            mysql_free_result($rs);
            if ($game_freq > 0) {
                $percentage = $game_freq*100/$total_minigame_freq;
                $jam = intval($avg_total_time / 3600);
                $menit = intval(intval($avg_total_time % 3600) / 60);
                $detik = intval(intval($avg_total_time % 3600) % 60);
                $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_overall_minigame_daily
                          (date_d, mini_game_id, mini_game_name, num, 
                           total, percentage, avg_time, 
                           jam, menit, detik)VALUES
                          ('".$this->date."','".($i+1)."','".$minigame_name."','".$game_freq."',
                           '".$total_minigame_freq."','".$percentage."','".$avg_total_time."',
                           '".$jam."','".$menit."','".$detik."')";
                //print $query . "\n";
                $rs = mysql_query($query, $this->conn);
                $this->log->status("insert minigame #".($i+1)." to ".$SCHEMA_REPORT.".rp_overall_minigame_daily",$rs);
            }
        }
    }

    public function getOverallRaceCount($use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $race_count = 0;
        $query = "SELECT COUNT(id) freq FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
                  WHERE action_id = 4";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $race_count = intval($row['freq']);
        }
        mysql_free_result($rs);
        return $race_count;
    }

    public function getOverallCarModificationCount() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $modification_count = 0;
        $query = "SELECT COUNT(id) freq FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
                  WHERE action_id IN (5,16)";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $modification_count = intval($row['freq']);
        }
        mysql_free_result($rs);
        return $modification_count;
    }

    private function insertGetDistDaily($location, $member_count) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $query = "INSERT INTO ".$SCHEMA_REPORT.".rp_geo_dist_daily
                  (date_d, location, num)VALUES
                  ('" . $this->date . "', '" . $location . "',".$member_count.")
                  ON DUPLICATE KEY UPDATE num = num + VALUES(num)";
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert location '" . $location . "' to ".$SCHEMA_REPORT.".rp_geo_dist_daily",$rs);
        return $rs;
    }

    private function getCityArray() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $city_array = array();
        $query = "SELECT DISTINCT A.city AS id, C.city_name FROM ".$SCHEMA.".kana_member AS A
                  INNER JOIN ".$SCHEMA.".tbl_qr_city AS B
                  INNER JOIN ".$SCHEMA.".tbl_qr_city_reference AS C
                  ON B.code = C.code AND A.city = B.city_id
                  WHERE A.city IS NOT NULL";
        $rs = mysql_query($query, $this->conn);
        while ($row = mysql_fetch_assoc($rs)) {
            array_push($city_array, $row);
        }
        mysql_free_result($rs);
        return $city_array;
    }

    function insertOverallLocation() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $member_count = 0;
        $total_member_count = 0;

        //$tmp = array();

        //delete
        $query = "DELETE FROM ".$SCHEMA_REPORT.".rp_geo_dist_daily
                  WHERE date_d = '" . $this->date . "'";
        $rs = mysql_query($query, $this->conn);
        $this->log->status("DELETE location data to ".$SCHEMA_REPORT.".rp_geo_dist_daily",$rs);

        //insert for others
        $query = "SELECT COUNT(id) as num FROM ".$SCHEMA.".kana_member
                  WHERE city IS NULL";
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $member_count = intval($row['num']);
        }
        mysql_free_result($rs);
        $total_member_count += $member_count;
        $this->insertGetDistDaily("other", $member_count);
        //$tmp [ "other" ] =  $member_count;
        //print $member_count . "\n";


        $city_array = $this->getCityArray();
        foreach ($city_array as $city_tuple) {
            $member_count = 0;
            $query = "SELECT COUNT(id) as num FROM ".$SCHEMA.".kana_member
            		  WHERE city = '" . mysql_real_escape_string($city_tuple['id']) . "'";
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                $member_count = intval($row['num']);
            }
            mysql_free_result($rs);
            $total_member_count += $member_count;
            //            if (array_key_exists($city_tuple['city_name'], $tmp)) {
            //                $tmp [ $city_tuple['city_name'] ] +=  $member_count;
            //            } else {
            //                $tmp [ $city_tuple['city_name'] ] =  $member_count;
            //            }
            //print $member_count . "\n";
            $this->insertGetDistDaily($city_tuple['city_name'], $member_count);
        }
        //        print_r($tmp);
        $query = "UPDATE ".$SCHEMA_REPORT.".rp_geo_dist_daily SET total = ".$total_member_count."
        		  WHERE date_d = '".$this->date."'";
        $rs = mysql_query($query, $this->conn);
        $query = "UPDATE ".$SCHEMA_REPORT.".rp_geo_dist_daily SET percentage = num*100/total
        		  WHERE date_d = '".$this->date."'";
        $rs = mysql_query($query, $this->conn);
        $this->log->status("update percentage in ".$SCHEMA_REPORT.".rp_geo_dist_daily",$rs);
    }

    public function getOverallMinigamePlayCount() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $query = "SELECT COUNT(id) num 
                  FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_mini_game
                  WHERE game_id IN (1,2,3,4) AND n_status = 1";            
        $rs = mysql_query($query, $this->conn);
        $total_play_count = 0;
        if ($row = mysql_fetch_assoc($rs)) {
            $total_play_count = intval($row['num']);
        }
        mysql_free_result($rs);
        return $total_play_count;
    }

    function insertOverallGender() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $last_id = 0;
        $nrows = 1000;
        $age_gender_array = array("M"=>array("13-17"=>0,"18-21"=>0,"22-28"=>0,">= 29"=>0,"others"=>0),
                                  "F"=>array("13-17"=>0,"18-21"=>0,"22-28"=>0,">= 29"=>0,"others"=>0),
                                  "U"=>array("13-17"=>0,"18-21"=>0,"22-28"=>0,">= 29"=>0,"others"=>0));
        $age_gender_array = array("M"=>array("13-17"=>0,"18-21"=>0,"22-28"=>0,">= 29"=>0,"others"=>0),
                                  "F"=>array("13-17"=>0,"18-21"=>0,"22-28"=>0,">= 29"=>0,"others"=>0),
                                  "U"=>array("13-17"=>0,"18-21"=>0,"22-28"=>0,">= 29"=>0,"others"=>0));
        while (1) {
            $prev_id = $last_id;
            $query = "SELECT id, sex, YEAR(CURRENT_DATE) - YEAR(birthday) AS age
                      FROM ".$SCHEMA.".kana_member 
                      WHERE id > " . $last_id . " LIMIT " . $nrows;
            $rs = mysql_query($query, $this->conn);
            while ($row = mysql_fetch_assoc($rs)) {
                $last_id = intval($row['id']);
                if (strcasecmp(trim($row['sex']),"m") == 0) {
                    if (13 <= $row['age'] && $row['age'] <= 17) {
                        $age_gender_array["M"]["13-17"]++;
                    } else if (18 <= $row['age'] && $row['age'] <= 21) {
                        $age_gender_array["M"]["18-21"]++;
                    } else if (22 <= $row['age'] && $row['age'] <= 28) {
                        $age_gender_array["M"]["22-28"]++;
                    } else if (29 <= $row['age']) {
                        $age_gender_array["M"][">= 29"]++;
                    } else {
                        $age_gender_array["M"]["others"]++;
                    }
                } else if (strcasecmp(trim($row['sex']),"f") == 0) {
                    if (13 <= $row['age'] && $row['age'] <= 17) {
                        $age_gender_array["F"]["13-17"]++;
                    } else if (18 <= $row['age'] && $row['age'] <= 21) {
                        $age_gender_array["F"]["18-21"]++;
                    } else if (22 <= $row['age'] && $row['age'] <= 28) {
                        $age_gender_array["F"]["22-28"]++;
                    } else if (29 <= $row['age']) {
                        $age_gender_array["F"][">= 29"]++;
                    } else {
                        $age_gender_array["F"]["others"]++;
                    }
                } else {
                    if (13 <= $row['age'] && $row['age'] <= 17) {
                        $age_gender_array["U"]["13-17"]++;
                    } else if (18 <= $row['age'] && $row['age'] <= 21) {
                        $age_gender_array["U"]["18-21"]++;
                    } else if (22 <= $row['age'] && $row['age'] <= 28) {
                        $age_gender_array["U"]["22-28"]++;
                    } else if (29 <= $row['age']) {
                        $age_gender_array["U"][">= 29"]++;
                    } else {
                        $age_gender_array["U"]["others"]++;
                    }
                }
            }
            mysql_free_result($rs);
            if ($prev_id == $last_id) {
                break;
            }
            $prev_id = $last_id;
        }
        //print_r($age_gender_array);
        $gender_keys = array_keys($age_gender_array);
        foreach ($gender_keys as $gender_key) {
            //print $gender_key . "\n";
            $age_keys = array_keys($age_gender_array[$gender_key]);
            if (strcasecmp($gender_key, "U") != 0) {
                foreach ($age_keys as $age_key) {
                    //print $age_key . "\n";
                    if (strcasecmp($age_key, "others") != 0) {
                        //print "[$gender_key][$age_key]=" . $age_gender_array[$gender_key][$age_key] . "\n";
                        $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_overall_gender_daily
                                  (date_d,age_range,sex,people_count)VALUES 
                                  ('" . $this->date . "','" . $age_key . "','" . $gender_key . "','" . $age_gender_array[$gender_key][$age_key] . "')";
                        $rs = mysql_query($query, $this->conn);
                        $this->log->status("insert location [" . $age_key . "][" . $gender_key . "] to ".$SCHEMA_REPORT.".rp_overall_gender_daily",$rs);
                    }
                }
            }
        }
    }

    function insertOverallRedeem($use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        
        //get list of merchandise
        $query = "SELECT id, item_name FROM ".$SCHEMA.".rr_merchandise";
        $rs = mysql_query($query, $this->conn);
        $merchandise_array = array();
        while ($row = mysql_fetch_assoc($rs)) {
            array_push($merchandise_array, $row);
        }
        mysql_free_result($rs);

        foreach ($merchandise_array as $merchandise) {
            $merchandise_id = intval($merchandise['id']);
            $merchandise_name = mysql_real_escape_string(trim($merchandise['item_name']));
            if ($use_timestamp) {
                $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_overall_redeem_daily
                          (date_d, merchandise_id, merchandise_name, redeem_count)
                          SELECT '" . $this->date . "', merchandise_id, '" . $merchandise_name . "', COUNT(id)
                          FROM ".$SCHEMA.".rr_purchase_merchandise 
                          WHERE approve_date_ts >= " . $this->date_ts . " AND approve_date_ts < " . ($this->date_ts+86400) . "
                          AND merchandise_id = " . $merchandise_id . "
                          GROUP BY merchandise_id";
            }else{
                $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_overall_redeem_daily
                          (date_d, merchandise_id, merchandise_name, redeem_count)
                          SELECT '" . $this->date . "', merchandise_id, '" . $merchandise_name . "', COUNT(id)
                          FROM ".$SCHEMA.".rr_purchase_merchandise 
                          WHERE DATE(approve_date) = '" . $this->date . "' AND merchandise_id = " . $merchandise_id . "
                          GROUP BY merchandise_id";
            }
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            $this->log->status("insert merchandise #" . $merchandise_id . " to ".$SCHEMA_REPORT.".rp_overall_redeem_daily",$rs);
        }
    }

    function getOverallUserParticipant() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $participant_count = 0;
        $query = "SELECT COUNT(user_id) num FROM ".$SCHEMA_REPORT.".rp_user_participant";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $participant_count = intval($row['num']);
        }
        mysql_free_result($rs);
        return $participant_count;
    }

    private function getActiveUser($use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $active_users = array();
        $query = "SELECT DISTINCT user_id FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        while ($row = mysql_fetch_assoc($rs)) {
            array_push($active_users, $row['user_id']);
        }
        mysql_free_result($rs);
        return $active_users;
    }

    private function insertActivityTime($act_id, $user_id, $action_id, $date_ts, $duration) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_user_activity_time
                  (id, user_id, action_id, date_ts, duration)VALUES
                  ('".$act_id."','".$user_id."','".$action_id."','".$date_ts."','".$duration."')";
        //print $query . "\n";
        return mysql_query($query, $this->conn);
    }

    function updateActivityDistribution() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $rep = array();
        $query = "SELECT action_id, SUM(duration) time_total,
                    COUNT(duration) num, AVG(duration) avg_total
                  FROM ".$SCHEMA_REPORT.".rp_user_activity_time
                  WHERE date_ts BETWEEN ".$this->date_ts." AND ".($this->date_ts+86400)."
                  GROUP BY action_id";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        while ($row = mysql_fetch_assoc($rs)) {
            array_push($rep, $row);
        }
        mysql_free_result($rs);
        if (sizeof($rep) > 0) {
            foreach ($rep as $row) {
                $action_id = $row['action_id'];
                if ($action_id == 7) {
                    $page_times = array();
                    $query = "SELECT B.action_id, A.action_value, SUM(B.duration) time_total,
                                COUNT(B.duration) num, AVG(B.duration) avg_total
                              FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log AS A 
                              INNER JOIN ".$SCHEMA_REPORT.".rp_user_activity_time AS B
                              ON A.id = B.id
                              WHERE A.action_id = 7 GROUP BY A.action_id, A.action_value";
                    if ($this->verbose) {print $query . "\n";}
                    $rs = mysql_query($query, $this->conn);
                    while ($row = mysql_fetch_assoc($rs)) {
                        array_push($page_times, $row);
                    }
                    mysql_free_result($rs);

                    if (sizeof($page_times) > 0) {
                        $other_time_total = 0;
                        $other_num = 0;
                        foreach ($page_times as $page_time) {
                            $page_name = mysql_escape_string("page:".$page_time['action_value']);
                            $check_id = 0;
                            $query = "SELECT activity_id FROM ".$SCHEMA_REPORT.".rp_activity_dist_daily
                                      WHERE date_d = '".$this->date."' AND activity_name = '".$page_name."'";
                            if ($this->verbose) {print $query . "\n";}
                            $rs = mysql_query($query, $this->conn);
                            if ($row = mysql_fetch_assoc($rs)) {
                                $check_id = intval($row[activity_id]);
                            }
                            mysql_free_result($rs);

                            if ($check_id > 0) {
                                $time_total = $page_time['time_total'];
                                $avg_total = $page_time['avg_total'];
                                $query = "UPDATE ".$SCHEMA_REPORT.".rp_activity_dist_daily
                                          SET time_total = '".$time_total."', avg_total = '".$avg_total."'
                                          WHERE date_d = '".$this->date."' AND activity_id = '".$action_id."'
                                          AND activity_name ='".$page_name."'";
                                $rs = mysql_query($query, $this->conn);
                                $this->log->status("update action #".$action_id.":".$page_name." in ".$SCHEMA_REPORT.".rp_activity_dist_daily",$rs);

                            } else {
                                $other_time_total += $page_time['time_total'];
                                $other_num += $page_time['num'];
                            }
                        }
                        if ($other_time_total > 0) {
                            $time_total = $other_time_total;
                            $avg_total = $other_time_total/$other_num;
                            $page_name = "page:other";
                            $query = "UPDATE ".$SCHEMA_REPORT.".rp_activity_dist_daily
                                          SET time_total = '".$time_total."', avg_total = '".$avg_total."'
                                          WHERE date_d = '".$this->date."' AND activity_id = '".$action_id."'
                                          AND activity_name ='".$page_name."'";
                            $rs = mysql_query($query, $this->conn);
                            $this->log->status("update action #".$action_id.":".$page_name." in ".$SCHEMA_REPORT.".rp_activity_dist_daily",$rs);

                        }

                    }
                    unset($page_times);
                } else {
                    $time_total = $row['time_total'];
                    $avg_total = $row['avg_total'];
                    $query = "UPDATE ".$SCHEMA_REPORT.".rp_activity_dist_daily
                          SET time_total = '".$time_total."', avg_total = '".$avg_total."'
                          WHERE date_d = '".$this->date."' AND activity_id = '".$action_id."';";
                    $rs = mysql_query($query, $this->conn);
                    $this->log->status("update action #".$action_id." in ".$SCHEMA_REPORT.".rp_activity_dist_daily",$rs);
                }
            }
        }
        unset($rep);
    }

    public static function prepareTempReport($conn, $log, $yesterday_ts, $yesterday) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        
        $query = "TRUNCATE ".$SCHEMA_REPORT_TEMP.".tbl_activity_time";
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "INSERT INTO ".$SCHEMA_REPORT_TEMP.".tbl_activity_time
                  SELECT * FROM ".$SCHEMA.".tbl_activity_time
                  WHERE ping_time_ts >= " . $yesterday_ts . " AND ping_time_ts < " . ($yesterday_ts+86400);
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "TRUNCATE ".$SCHEMA_REPORT_TEMP.".tbl_activity_time2";
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "INSERT INTO ".$SCHEMA_REPORT_TEMP.".tbl_activity_time2
                  SELECT * FROM ".$SCHEMA.".tbl_activity_time
                  WHERE ping_time_ts >= " . $yesterday_ts . " AND ping_time_ts < " . ($yesterday_ts+90000);
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "TRUNCATE ".$SCHEMA_REPORT_TEMP.".tbl_activity_log";
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "INSERT INTO ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
                  SELECT * FROM ".$SCHEMA.".tbl_activity_log
                  WHERE date_ts >= " . $yesterday_ts . " AND date_ts < " . ($yesterday_ts+86400);
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "TRUNCATE ".$SCHEMA_REPORT_TEMP.".tbl_activity_log2";
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "INSERT INTO ".$SCHEMA_REPORT_TEMP.".tbl_activity_log2
                  SELECT * FROM ".$SCHEMA.".tbl_activity_log
                  WHERE date_ts >= " . $yesterday_ts . " AND date_ts < " . ($yesterday_ts+90000);
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);
        
        $query = "TRUNCATE ".$SCHEMA_REPORT_TEMP.".tbl_exp_point";
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "INSERT INTO ".$SCHEMA_REPORT_TEMP.".tbl_exp_point
                  SELECT * FROM ".$SCHEMA.".tbl_exp_point
                  WHERE date_time_ts >= " . $yesterday_ts . " AND date_time_ts < " . ($yesterday_ts+86400);
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "TRUNCATE ".$SCHEMA_REPORT_TEMP.".racing_history";
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "INSERT INTO ".$SCHEMA_REPORT_TEMP.".racing_history
                  SELECT * FROM ".$SCHEMA_GAME.".racing_history
                  WHERE DATE(date_time) = '" . $yesterday . "'";
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "TRUNCATE ".$SCHEMA_REPORT_TEMP.".tbl_purchase_part";
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "INSERT INTO ".$SCHEMA_REPORT_TEMP.".tbl_purchase_part
                  SELECT * FROM ".$SCHEMA.".tbl_purchase_part
                  WHERE date_time_ts >= " . $yesterday_ts . " AND date_time_ts < " . ($yesterday_ts+86400);
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "TRUNCATE ".$SCHEMA_REPORT_TEMP.".tbl_user_points";
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "INSERT INTO ".$SCHEMA_REPORT_TEMP.".tbl_user_points
                  SELECT * FROM ".$SCHEMA.".tbl_user_points
                  WHERE submit_date_ts >= " . $yesterday_ts . " AND submit_date_ts < " . ($yesterday_ts+86400);
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "TRUNCATE ".$SCHEMA_REPORT_TEMP.".tbl_activity_mini_game";
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);

        $query = "INSERT INTO ".$SCHEMA_REPORT_TEMP.".tbl_activity_mini_game
                  SELECT * FROM ".$SCHEMA.".tbl_activity_mini_game
                  WHERE start_date_time >= '" . $yesterday . " 00:00:00' 
                  AND start_date_time <= '" . $yesterday . " 23:59:59'";
        $rs = mysql_query($query, $conn);
        $log->status($query,$rs);
    }
}

?>
