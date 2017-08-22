<?php 

/**
* Class EventsModel
* @author Irvan Fanani
*/
class EventsModel extends SQLData
{
	
	function __construct()
	{
		parent::SQLData();	
	}

	function getListEvents($start=0)
	{
		$this->open(0);	
		$query = "SELECT e.*, m.name FROM EVENTS e JOIN social_member m ON m.id=e.user_id ORDER BY posted_date DESC LIMIT $start,5;";
		$words = $this->fetch($query, true);
		
		$word_array = array();
		foreach($words as $key => $value) {
			array_push($word_array, "'".$value['kata2']."'");
		}

		$keywords = implode(',', $word_array);
		
		$query_keyword = "SELECT keyword, avg_cpc, jum_imp as imp, jum_hit as hit, ctr, jum_guna, max_bid,min_bid 
    						FROM db_web3.sitti_keywords_simulasi 
    						WHERE keyword IN (".$keywords.")";

		$suggestion_words = $this->fetch($query_keyword, true);

		//var_dump($suggestion_words);

		return $suggestion_words;
	}
}

?>