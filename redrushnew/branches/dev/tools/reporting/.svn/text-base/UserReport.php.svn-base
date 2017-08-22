<?php
include_once "bootstraps.php";
include_once "libs/logger.php";
error_reporting(0);


class UserReport {
    //global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
    protected $user_id;
    protected $log;
    protected $conn;
    protected $date;
    protected $date_ts;
    private $verbose = false;

    public function UserReport($user_id, $conn, $log) {
        $this->user_id = $user_id;
        $this->conn = $conn;
        $this->log = $log;
        //print $this->user_id . "::" . intval($this->user_id) . "\n";
    }

    public function user_id($user_id = null) {
        if (is_null($user_id)) {
            return $user_id;
        } else {
            $this->user_id = $user_id;
        }
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
        if (intval($this->user_id) > 0) {
            $this->date = $date;
            $this->date_ts = $date_ts;

            $login_count = $this->getUserLoginCount();

            if ($login_count > 0) {
                $login_time_array = $this->getUserLoginTime();
                $result = $this->calculateLoginTime($login_time_array, $login_count);
                $total_login_time = $result[0];
                $avg_time_per_login = $result[1];
                $page_view_count = $this->getUserPageviewCount();
                $page_view_per_login = $page_view_count * 1.0 / $login_count;
                $redeem_count = $this->getUserRedeemCount();
                $activity_arr = $this->getUserActivityHistory();
                $user_point = $this->getUserPoint();
                $exp_point = $this->getUserExpPoint();
                $buying_part_point = $this->getUserBuyingPartPoint();
                $redeem_merchandise_point = $this->getUserRedeemMerchandisePoint();
                //var_dump($activity_arr);
                if ($this->verbose) {
                    print $this->user_id . "::" . $login_count . "->" . $total_login_time . "->" . $avg_time_per_login . "->" . $redeem_count . "->" . $user_point . "\n\n";
                }
                $this->insertUserDailyReport($page_view_per_login, $avg_time_per_login, $redeem_count,
                $page_view_count, $total_login_time, $login_count, $exp_point, $buying_part_point, $redeem_merchandise_point);
                $this->insertUserPointTotal();
                $this->insertUserLoginHistory();
                $this->insertUserRaceData();
                $this->insertUserParticipant();
            } else {
                $this->log->info("User ID = " . $this->user_id . " did not login");
            }
            $this->insertUserRaceTimeData();
            $this->insertUserActivityTimeDistribution();
        } else {
            $this->log->info("User ID = 0");
        }
    }

    public function getUserRedeemMerchandisePoint($use_timestamp = false) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $login_count = 0;
        if ($use_timestamp) {
            $query = "SELECT COUNT(id) num FROM ".$SCHEMA.".rr_purchase_merchandise
    			      WHERE purchase_date_ts >= " . $this->date_ts . " AND purchase_date_ts < " . ($this->date_ts+86400) . " 
    			      AND user_id IN (" . $this->user_id . ") GROUP BY user_id";
        } else {
            $query = "SELECT COUNT(id) num FROM ".$SCHEMA.".rr_purchase_merchandise
			          WHERE DATE(purchase_date) = '" . $this->date . "' 
			          AND user_id = '" . $this->user_id . "' LIMIT 1";
        }
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            #print $row['user_id'] . "->" . $row['num'] . "\n";
            $login_count = $row['num'];
        }
        mysql_free_result($rs);
        return $login_count;
    }

    public function getUserBuyingPartPoint($use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $login_count = 0;
        $query = "SELECT COUNT(id) num FROM ".$SCHEMA_REPORT_TEMP.".tbl_purchase_part
                  WHERE user_id IN (" . $this->user_id . ") GROUP BY user_id";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            #print $row['user_id'] . "->" . $row['num'] . "\n";
            $login_count = $row['num'];
        }
        mysql_free_result($rs);
        return $login_count;
    }

    public function getUserLoginCount($use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $login_count = 0;
        $query = "SELECT COUNT(id) num FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
			      WHERE user_id IN (" . $this->user_id . ") AND action_id = 1 
			      GROUP BY user_id";
        //print $query . "\n";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            #print $row['user_id'] . "->" . $row['num'] . "\n";
            $login_count = $row['num'];
        }
        mysql_free_result($rs);
        return $login_count;
    }

    public function getUserPageviewCount($use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $login_count = 0;
        $query = "SELECT COUNT(id) num FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
			      WHERE user_id IN (" . $this->user_id . ") AND action_id IN (2,7) 
			      GROUP BY user_id";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            #print $row['user_id'] . "->" . $row['num'] . "\n";
            $login_count = $row['num'];
        }
        mysql_free_result($rs);
        return $login_count;
    }

    function getUserLoginTime($use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $login_count = 0;
        $query = "SELECT id, ping_time_ts FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_time
			      WHERE user_id IN (" . $this->user_id . ") ORDER BY id";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $idx = -1;
        $login_time = 0;
        $last_login_time = 0;
        $time_array = array();
        while ($row = mysql_fetch_assoc($rs)) {
            //print $row['id'] . "->" . $row['ping_time_ts'] . " -> " . $login_time . "\n";
            if ($row['ping_time_ts'] > $last_login_time+60) {
                #print $row['id'] . "->" . $row['ping_time_ts'] . "\n";
                if ($idx > -1) {
                    $time_array[$idx] = $login_time;
                }
                $idx++;
                $login_time = 0;
            } else {
                $login_time = $login_time + $row['ping_time_ts'] - $last_login_time;
            }
            $last_login_time = $row['ping_time_ts'];
        }
        if ($idx > -1) {
            #print "->" . $last_login_time . "\n";
            $time_array[$idx] = $login_time;
        }
        #print $user_ids . "->";print_r($time_array);
        mysql_free_result($rs);
        return $time_array;
    }

    function getUserActivityHistory($num = 10, $use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $login_count = 0;
        $query = "SELECT id, action_id, action_value
                  FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
		          WHERE user_id IN (" . $this->user_id . ") 
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

    private function calculateLoginTime($login_time_array, $login_count) {
        $avg = 0;
        $total_time = 0;
        $login_time_array_len = sizeof($login_time_array);
        if ($login_time_array_len > 0) {
            //print_r($login_time_array);
            foreach ($login_time_array as $login_time) {
                $total_time += $login_time;
            }
            $avg = $total_time * 1.0 / $login_count;
        }
        return array($total_time,$avg);
    }

    function getUserRedeemCount($use_timestamp = true) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $redeem_count = 0;
        $query = "SELECT user_id, COUNT(id) num
                  FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
		          WHERE user_id IN (" . $this->user_id . ") AND action_id = 6 
		          GROUP BY user_id";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            #print $row['user_id'] . "->" . $row['num'] . "\n";
            $redeem_count = $row['num'];
        }
        mysql_free_result($rs);
        return $redeem_count;
    }

    function getUserPoint($use_timestamp = false) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $user_point = 0;
        $query = "SELECT SUM(POINT) points
                  FROM ".$SCHEMA_REPORT_TEMP.".tbl_user_points
			      WHERE user_id IN (" . $this->user_id . ")";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            #print $row['user_id'] . "->" . $row['num'] . "\n";
            $user_point = intval($row['points']);
        }
        mysql_free_result($rs);
        return $user_point;
    }

    function getUserExpPoint($use_timestamp = false) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $user_point = 0;
        $query = "SELECT SUM(score) points FROM ".$SCHEMA_REPORT_TEMP.".tbl_exp_point
			      WHERE user_id IN (" . $this->user_id . ")";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            #print $row['user_id'] . "->" . $row['num'] . "\n";
            $user_point = intval($row['points']);
        }
        mysql_free_result($rs);
        return $user_point;
    }

    function insertUserDailyReport($page_view_per_login, $time_per_login, $redeem_count,
    $page_view_count, $login_time, $login_count, $exp_point, $buying_part_point, $redeem_merchandise_point) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_user_daily
				(date_d, user_id, page_view_per_login, 
				 time_per_login, redeem_count, page_view_count, 
				 login_time, login_count, exp_point, 
				 buying_part_point, redeem_merchandise_point)VALUES 
				('" . $this->date . "','" . $this->user_id . "','" . $page_view_per_login . "',
				 '" . $time_per_login . "','" . $redeem_count . "','" . $page_view_count . "',
				 '" . $login_time . "','" . $login_count . "','" . $exp_point . "',
				 '" . $buying_part_point . "','" . $redeem_merchandise_point . "')";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status('insert to '.$SCHEMA_REPORT.'.rp_user_daily',$rs);
        return $rs;
    }

    function insertUserPointTotal() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_user_point_total
				  SELECT user_id, SUM(exp_point), NOW()
				  FROM ".$SCHEMA_REPORT.".rp_user_daily
				  WHERE user_id IN ('" . $this->user_id . "')";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status('insert to '.$SCHEMA_REPORT.'.rp_user_point_total',$rs);
        return $rs;
    }

    function insertUserLoginHistory() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $time = null;
        $query = "SELECT first_login_time FROM ".$SCHEMA_REPORT.".rp_user_login_history
                  WHERE user_id = " . $this->user_id;
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $time = $row['date_time'];
        }
        mysql_free_result($rs);
        if (is_null($time)) {
            $query = "SELECT date_time, date_ts FROM ".$SCHEMA.".tbl_activity_log
				  WHERE user_id = " . $this->user_id . " AND action_id = 1 ORDER BY id LIMIT 1";
            if ($this->verbose) {print $query . "\n";}
            $ts = 0;
            $time = "0000-00-00 00:00:00";
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                $ts = intval($row['date_ts']);
                if ($ts > 0) {
                    $time = $row['date_time'];
                }
            }
        }

        if ($this->verbose) {print $query . "\n";}
        mysql_free_result($rs);
        if (!is_null($time)) {
            $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_user_login_history
					  (user_id, first_login_time, login_count, login_time, last_update)
					  SELECT user_id, '" . $time . "', SUM(login_count), SUM(login_time), NOW()
					  FROM ".$SCHEMA_REPORT.".rp_user_daily
					  WHERE user_id IN ('" . $this->user_id . "')
					  GROUP BY user_id";
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            $this->log->status("insert to ".$SCHEMA_REPORT.".rp_user_login_history",$rs);
        } else {
            //$this->log->info('insert to ".$SCHEMA_REPORT.".rp_user_login_history');
            print "$user_id has no login history\n";
        }
        return $rs;
    }

    function insertUserRaceData() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $freq = 0;
        $challenging = 0;
        $challenged = 0;
        $win = 0;
        $lose = 0;

        $query = "SELECT COUNT(id) freq FROM ".$SCHEMA_REPORT_TEMP.".racing_history
				  WHERE (user1_id = " . $this->user_id . " OR user2_id = " . $this->user_id . ")";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $freq = intval($row['freq']);
        }
        mysql_free_result($rs);

        if ($freq > 0) {
            $win = 0;
            $query = "SELECT COUNT(id) win FROM ".$SCHEMA_REPORT_TEMP.".racing_history
    				  WHERE winner = " . $this->user_id;
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                $win = intval($row['win']);
            }
            mysql_free_result($rs);
            $lose = $freq - $win;

            $challenging = 0;
            $query = "SELECT COUNT(id) challenging FROM ".$SCHEMA_REPORT_TEMP.".racing_history
    				  WHERE user1_id = " . $this->user_id;
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                $challenging = intval($row['challenging']);
            }
            mysql_free_result($rs);
            $challenged = $freq - $challenging;
            $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_user_race_daily
					  (date_d, user_id, freq, 
					   challenging, challenged, win, lose)VALUES
					  ('" . $this->date . "', " . $this->user_id . ", " . $freq . ", 
					   " . $challenging . ", " . $challenged . ", " . $win . ", " . $lose . ")";
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            $this->log->status("insert to ".$SCHEMA_REPORT.".rp_user_race_daily",$rs);

            $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_user_race_total
                      (user_id, freq, challenging, challenged, win, lose, last_update)
                      SELECT user_id, SUM(freq), SUM(challenging), SUM(challenged), SUM(win), SUM(lose), NOW()
                      FROM ".$SCHEMA_REPORT.".rp_user_race_daily
                      WHERE user_id = " . $this->user_id;
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            $this->log->status("insert to ".$SCHEMA_REPORT.".rp_user_race_total",$rs);

            $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_user_race_level_daily
					  (date_d, user_id, opponent_level, freq)
            		  SELECT '" . $this->date . "', user1_id, racer2_level, COUNT(id) num 
            		  FROM ".$SCHEMA_REPORT_TEMP.".racing_history
					  WHERE user1_id = " . $this->user_id . "
					  GROUP BY racer2_level";
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            $this->log->status("insert to ".$SCHEMA_REPORT.".rp_user_race_level_daily",$rs);

            $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_user_race_level_total
					  (user_id, opponent_level, freq)
            		  SELECT user_id, opponent_level, SUM(freq) 
            		  FROM ".$SCHEMA_REPORT.".rp_user_race_level_daily
					  WHERE user_id = " . $this->user_id;
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            $this->log->status("insert to ".$SCHEMA_REPORT.".rp_user_race_level_total",$rs);
        }
        return $rs;
    }

    function insertUserParticipant() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $isParticipant = false;
        $query = "SELECT user_id FROM ".$SCHEMA_REPORT.".rp_user_participant
        		  WHERE user_id = " . $this->user_id;
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $isParticipant = true;
        }
        mysql_free_result($rs);


        if (!$isParticipant) {
            $query = "SELECT id FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
				      WHERE user_id = " . $this->user_id . " 
				      AND action_id IN (4,6,11,12,13,14,15)
				      LIMIT 1";
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                $isParticipant = true;
            }
            mysql_free_result($rs);

            if ($isParticipant) {
                $query = "INSERT IGNORE INTO ".$SCHEMA_REPORT.".rp_user_participant(user_id)VALUES('".$this->user_id."')";
                $rs = mysql_query($query, $this->conn);
                $this->log->status("insert user #" . $this->user_id . " to ".$SCHEMA_REPORT.".rp_user_participant",$rs);
            }
        }
    }

    public static function getAllUserOld($conn, $log) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $query = "SELECT id FROM ".$SCHEMA.".kana_member";
        $users = array();
        $i = 0;
        $rs = mysql_query($query, $conn);
        //print $conn . ":". $query . "\n";
        while ($row = mysql_fetch_assoc($rs)) {
            //print $i . " ";
            $users[$i++] = $row;
            //print $i . "\n";
        }
        mysql_free_result($rs);
        return $users;
    }

    public static function getAllUser($conn, $log) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        //$query = "SELECT id FROM ".$SCHEMA.".kana_member";
        $query = "SELECT DISTINCT user_id AS id FROM (
                    SELECT DISTINCT user1_id user_id FROM ".$SCHEMA_REPORT_TEMP.".racing_history
                    UNION ALL
                    SELECT DISTINCT user2_id user_id FROM ".$SCHEMA_REPORT_TEMP.".racing_history
                    UNION ALL
                    SELECT DISTINCT user_id FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log
                    UNION ALL
                    SELECT DISTINCT user_id FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_time
                    UNION ALL
                    SELECT DISTINCT user_id FROM ".$SCHEMA_REPORT_TEMP.".tbl_exp_point
                    UNION ALL
                    SELECT DISTINCT user_id FROM ".$SCHEMA_REPORT_TEMP.".tbl_purchase_part
                  ) AS A";
        $users = array();
        $i = 0;
        $rs = mysql_query($query, $conn);
        //print $conn . ":". $query . "\n";
        while ($row = mysql_fetch_assoc($rs)) {
            //print $i . " ";
            $users[$i++] = $row;
            //print $i . "\n";
        }
        mysql_free_result($rs);
        return $users;
    }

    function insertUserRaceTimeData() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $first_login_date = null;
        $last_level = 0;
        //get first_login n last_level of a user
        $query = "SELECT first_login date_time,date(first_login) first_login, last_level
                  FROM ".$SCHEMA_REPORT.".rp_user_race_help_data
                  WHERE user_id = " . $this->user_id . " AND date_d < '".$this->date."' 
                  ORDER BY date_d DESC LIMIT 1";
        //print $query . "\n";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);

        if ($row = mysql_fetch_assoc($rs)) {
            $first_login_datetime = $row['date_time'];
            $first_login_date = $row['first_login'];
            $last_level = intval($row['last_level']);
        }
        mysql_free_result($rs);
        //print "$first_login_datetime\n";

        if (is_null($first_login_date)) {
            //belum pernah diproses, maka ambil data login dan data baru
            $query = "SELECT date_time, date(date_time) first_login, DATEDIFF(CURRENT_DATE,DATE(date_time)) day_count
                      FROM ".$SCHEMA.".tbl_activity_log
                      WHERE user_id = " . $this->user_id . " AND action_id = 1 ORDER BY id LIMIT 1";
            //print $query . "\n";
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                $first_login_datetime = $row['date_time'];
                $first_login_date = $row['first_login'];
                $day_count = intval($row['day_count']);
            }
            mysql_free_result($rs);
            if (is_null($first_login_date)) {
                //belum pernah login, berarti tidak perlu diproses lagi
                return;
            }
            $last_level = 1;
        }
        //sudah pernah login
        //print "last level = $last_level , first_login_date = $first_login_datetime , \n";
        if (!is_null($first_login_date)) {
            $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_user_race_help_data
                      (date_d, user_id, first_login, last_level, last_update)VALUES
                      ('".$this->date."','" . $this->user_id . "','".$first_login_datetime."',".$last_level.",NOW())";
            //print $query . "\n";
            $rs = mysql_query($query, $this->conn);
            $this->log->status('insert user #' . $this->user_id . ' to '.$SCHEMA_REPORT.'.rp_user_race_help_data',$rs);

            $query = "SELECT total_time FROM ".$SCHEMA_REPORT.".rp_user_race_level_data
                  WHERE user_id = ".$this->user_id." AND date_d < '".$this->date."' 
                  AND LEVEL = ".$last_level." ORDER BY date_d DESC LIMIT 1";
            //print $query . "\n";
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            if ($row = mysql_fetch_assoc($rs)) {
                $total_time = intval($row['total_time']);
                $prev_level_ts = $this->date_ts;
                print "prev_level_ts = $prev_level_ts from last date\n";
            } else {
                $total_time = 0;
                $date_time_dt = new DateTime(trim($first_login_datetime));
                //$date_time_dt = new DateTime("2012-04-02");
                //$date_time_dt = date_create(trim($first_login_datetime));
                //$prev_level_ts = $date_time_dt->getTimestamp();
                //$prev_level_ts = date_timestamp_get($date_time_dt);
                $prev_level_ts = $date_time_dt->format("U");
                unset($date_time_dt);
                print "prev_level_ts = $prev_level_ts from first login ($first_login_datetime)\n";
            }
            mysql_free_result($rs);

            //        $query = "SELECT first_login date_time,date(first_login) first_login, last_level
            //                  FROM ".$SCHEMA_REPORT.".rp_user_race_help_data
            //                  WHERE user_id = " . $this->user_id . " AND date_d < '".$this->date."'
            //                  ORDER BY date_d DESC LIMIT 1";
            //        //print $query . "\n";
            //        if ($this->verbose) {print $query . "\n";}
            //        $rs = mysql_query($query, $this->conn);
            //        if ($row = mysql_fetch_assoc($rs)) {
            //            $first_login_datetime = $row['date_time'];
            //            $first_login_date = $row['first_login'];
            //            $last_level = intval($row['last_level']);
            //        }
            //        mysql_free_result($rs);

            //sudah pernah diproses, maka ambil data baru
            while (true) {
                print "last level = $last_level , total_time = $total_time , prev_level_ts = $prev_level_ts \n";
                $hasResult = false;
                $query = "SELECT MIN(date_time) date_time FROM (
                            SELECT date_time FROM (
                                SELECT date_time FROM ".$SCHEMA_REPORT_TEMP.".racing_history
                                WHERE (user1_id = " . $this->user_id . ")
                                AND racer1_level > ".$last_level." 
                                AND racer1_level <= ".($last_level+1)." ORDER BY id LIMIT 1
                            ) AS A
                            UNION ALL
                            SELECT date_time FROM (
                                SELECT date_time FROM ".$SCHEMA_REPORT_TEMP.".racing_history
                                WHERE (user2_id = " . $this->user_id . ") AND racer2_level > 1
                                AND racer1_level > ".$last_level." 
                                AND racer1_level <= ".($last_level+1)." ORDER BY id LIMIT 1
                            ) AS B
                          ) AS C";
                //print $query . "\n";
                $rs = mysql_query($query, $this->conn);
                if ($row = mysql_fetch_assoc($rs)) {
                    if (!is_null($row['date_time'])) {
                        $hasResult = true;
                        $new_level_time = $row['date_time'];
                        $date_time_dt = new DateTime(trim($new_level_time));
                        //$next_level_ts = $date_time_dt->getTimestamp();
                        $next_level_ts = $date_time_dt->format("U");
                        unset($date_time_dt);
                    }
                }
                mysql_free_result($rs);
                if (!$hasResult) {
                    break;
                }
                $diff_level_ts = $next_level_ts - $prev_level_ts + $total_time;
                //print "$next_level_ts - $prev_level_ts  + $total_time = $diff_level_ts\n";
                if ($diff_level_ts > 0) {
                    $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_user_race_level_data
                          (date_d,user_id, LEVEL, total_time, last_update) VALUES 
                          ('".$this->date."','".$this->user_id."','".$last_level."','".$diff_level_ts."',NOW())";
                    //print $query . "\n";
                    $rs = mysql_query($query, $this->conn);
                    $this->log->status('insert user #' . $this->user_id . ',level #'.$last_level.' to '.$SCHEMA_REPORT.'.rp_user_race_level_data',$rs);
                    $query = "UPDATE ".$SCHEMA_REPORT.".rp_user_race_help_data
                		  SET last_level = '".($last_level+1)."',  last_update = NOW()
						  WHERE user_id = '".$this->user_id."' AND date_d = '".$this->date."'";
                    //print $query . "\n";
                    $rs = mysql_query($query, $this->conn);
                    $this->log->status('update last level user #' . $this->user_id . ' to level #'.$last_level.' to '.$SCHEMA_REPORT.'.rp_user_race_help_data',$rs);
                }

                $prev_level_ts = $next_level_ts;
                $total_time = 0;
                $last_level++;
            }
            $diff_level_ts = $this->date_ts + 86400 - $prev_level_ts + $total_time;
            //print $this->date_ts . " + 86400 - $prev_level_ts = $diff_level_ts\n";
            if ($diff_level_ts > 0) {
                $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_user_race_level_data
                          (date_d,user_id, LEVEL, total_time, last_update) VALUES 
                          ('".$this->date."','".$this->user_id."','".$last_level."','".$diff_level_ts."',NOW())";
                //print $query . "\n";
                $rs = mysql_query($query, $this->conn);
                $this->log->status('insert user #' . $this->user_id . ',level #'.$last_level.' to '.$SCHEMA_REPORT.'.rp_user_race_level_data',$rs);
                $query = "UPDATE ".$SCHEMA_REPORT.".rp_user_race_help_data
                		  SET last_level = '".$last_level."',  last_update = NOW()
						  WHERE user_id = '".$this->user_id."' AND date_d = '".$this->date."'";
                //print $query . "\n";
                $rs = mysql_query($query, $this->conn);
                $this->log->status('update last level user #' . $this->user_id . ' to level #'.$last_level.' to '.$SCHEMA_REPORT.'.rp_user_race_help_data',$rs);
            }
        }


    }

    private function getActivityLog() {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $activity_log = array();
        $query = "SELECT id, action_id, action_value, date_ts, date_time 
                  FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_log2
                  WHERE user_id = ".$this->user_id." ORDER BY id";
        //print $query . "\n";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        while ($row = mysql_fetch_assoc($rs)) {
            array_push($activity_log, $row);
        }
        mysql_free_result($rs);
        return $activity_log;
    }
    private function getLastPingTime($user_id, $act_ts) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $last_ping_time = 0;
        $query = "SELECT ping_time_ts FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_time2
                  WHERE user_id = ".$this->user_id." 
                  AND ping_time_ts > ".$act_ts."
                  ORDER BY id DESC LIMIT 1";
        //print $query . "\n";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $last_ping_time = $row['ping_time_ts'];
        }
        mysql_free_result($rs);
        return $last_ping_time;
    }

    private function getLastPingTime2($cur_act_ts, $next_act_ts) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $last_ping_time = 0;
        $query = "SELECT ping_time_ts FROM ".$SCHEMA_REPORT_TEMP.".tbl_activity_time
                  WHERE user_id = ".$this->user_id." 
                  AND ping_time_ts > ".$cur_act_ts." AND ping_time_ts <= ".$next_act_ts."
                  ORDER BY id DESC LIMIT 1";
        //print $query . "\n";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $last_ping_time = $row['ping_time_ts'];
        }
        mysql_free_result($rs);
        return $last_ping_time;
    }

    private function insertActivityTime($act_id, $action_id, $act_ts, $duration) {
        global $SCHEMA, $SCHEMA_GAME, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        $query = "REPLACE INTO ".$SCHEMA_REPORT.".rp_user_activity_time
                  (id, user_id, action_id, date_ts, duration)VALUES
                  ('".$act_id."','".$this->user_id."','".$action_id."','".$act_ts."','".$duration."')";
        //print $query . "\n";
        $rs = mysql_query($query, $this->conn);
        $this->log->status('insert user #' . $this->user_id . ' activity time to '.$SCHEMA_REPORT.'.rp_user_activity_time',$rs);
        return $rs;
    }

    function insertUserActivityTimeDistribution() {
        $activity_log = $this->getActivityLog();
        //var_dump($activity_log);
        $activity_len = sizeof($activity_log);
        for($idx = 0 ; $idx<$activity_len ; $idx++){
            $cur_activity = $activity_log[$idx];
            $act_id = $cur_activity['id'];
            $action_id = $cur_activity['action_id'];
            $act_ts = $cur_activity['date_ts'];
            if ($act_ts > ($this->date_ts+86400)) {
                //timestamp bukan di hari kemaren, so berhenti
                break;
            }
            if ($action_id == 10) {
                //logout action
                $duration = 1;
            } else {
                if (($idx+1) < $activity_len) {
                    $next_activity = $activity_log[$idx+1];
                    //berarti masih ada activity berikutnya
                    $last_ping_time = $this->getLastPingTime2($act_ts, $next_activity['date_ts']);
                    if ($last_ping_time == 0) {
                        //tidak ada ping stlh aktivitas ini, artinya, durasi = 1
                        $duration = 1;
                    } else if (($next_activity['date_ts'] - $last_ping_time) <= 45) {
                        $duration = $next_activity['date_ts'] - $cur_activity['date_ts'];
                    } else {
                        $duration = $last_ping_time - $cur_activity['date_ts'];
                    }
                    //print $duration . " " . $cur_activity['date_ts'] . " " . $next_activity['date_ts'] . " " . $last_ping_time . "\n";
                } else {
                    //berarti current activity = last activity
                    $last_ping_time = $this->getLastPingTime($act_ts);
                    if ($last_ping_time == 0) {
                        //tidak ada ping time berikutnya
                        //15 dipilih karena itu waktu maksimal sebelum ping time berikutnya
                        $duration = 15;
                    } else {
                        //ada ping time berikutnya
                        $duration = $last_ping_time - $act_ts;
                    }
                    //print "use getLastPingTime:$user_id :: $act_ts :: $duration\n";
                }
            }
            $this->insertActivityTime($act_id, $action_id, $act_ts, $duration);
        }
        unset($activity_log);
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
