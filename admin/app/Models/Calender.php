<?php

namespace Appsolute\Backend\Models;

use R;
use Appsolute\Backend\Classes\Auth\Auth as AuthClass;
use Appsolute\Backend\Models\Validation\ValidationException;
use Appsolute\Backend\Classes\Utility;


Class Calender extends Database {

	
    /*public function getAll() { 
		try { 
			$monthNames = Array("January", "February", "March", "April", "May", "June", "July", 
			               "August", "September", "October", "November", "December");

			if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("n");
			if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");

			$cMonth = $_REQUEST["month"];
			$cYear = $_REQUEST["year"];
			 
			$prev_year = $cYear;
			$next_year = $cYear;
			$prev_month = $cMonth-1;
			$next_month = $cMonth+1;
			 
			if ($prev_month == 0 ) {
			    $prev_month = 12;
			    $prev_year = $cYear - 1;
			}
			if ($next_month == 13 ) {
			    $next_month = 1;
			    $next_year = $cYear + 1;
			}
			$timestamp = mktime(0,0,0,$cMonth,1,$cYear);
			$maxday = date("t",$timestamp);
			$thismonth = getdate ($timestamp);
			$startday = $thismonth['wday'];
			for ($i=0; $i<($maxday+$startday); $i++) {
			    if(($i % 7) == 0 ) echo "<tr>";
			    if($i < $startday) echo "<td></td>";
			    else echo "<td align='center' valign='middle' height='20px'>". ($i - $startday + 1) . "</td>";
			    if(($i % 7) == 6 ) echo "</tr>";
			}
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }*/


    public function __construct(){     
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
    }
     
    /********************* PROPERTY *******************/  
    private $dayLabels = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
     
    private $currentYear=0;
     
    private $currentMonth=0;
     
    private $currentDay=0;
     
    private $currentDate=null;
     
    private $daysInMonth=0;
     
    private $naviHref= null;
     
    /********************* PUBLIC **********************/  
        
    
    //print out the calendar
    
    public function show() {
    	try { 
	        $year  == null;
	         
	        $month == null;
	         
	        if(null==$year&&isset($_GET['year'])){
	 
	            $year = $_GET['year'];
	         
	        }else if(null==$year){
	 
	            $year = date("Y",time());  
	         
	        }          
	         
	        if(null==$month&&isset($_GET['month'])){
	 
	            $month = $_GET['month'];
	         
	        }else if(null==$month){
	 
	            $month = date("m",time());
	         
	        }                  
	         
	        $this->currentYear=$year;
	         
	        $this->currentMonth=$month;
	         
	        $this->daysInMonth=$this->_daysInMonth($month,$year);  
	         
	        $content='<div id="calendar">'.
	                        '<div class="box">'.
	                        $this->_createNavi().
	                        '</div>'.
	                        '<div class="box-content">'.
	                                '<ul class="label">'.$this->_createLabels().'</ul>';   
	                                $content.='<div class="clear"></div>';     
	                                $content.='<ul class="dates">';    
	                                 
	                                $weeksInMonth = $this->_weeksInMonth($month,$year);

	                                // Create weeks in a month
	                                for( $i=0; $i<$weeksInMonth; $i++ ){
	                                     
	                                    //Create days in a week
	                                    for($j=1;$j<=7;$j++){
	                                        $content.=$this->_showDay($i*7+$j);

	                                    }
	                                }
	                                 
	                                $content.='</ul>';
	                                 
	                                $content.='<div class="clear"></div>';     
	             
	                        $content.='</div>';
	                 
	        $content.='</div>';
	        return $content;
	    } catch(ValidationException $e) {
	    	$this->errors[] = $e->getMessage();
			return FALSE;
	    }   
    }
     
    /********************* PRIVATE **********************/ 
    //create the li element for ul
    
    private function _showDay($cellNumber){
         
        if($this->currentDay==0){
             
            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
              
            if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                 
                $this->currentDay=1;   
                 
            }
        }
         
        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
             
            $this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));
             
            $cellContent = $this->currentDay;

            $this->currentDay++;   
             
        }else{
             
            $this->currentDate =null;
 
            $cellContent=null;
        }
        if($this->currentDate == date('Y-m-d')) {
            '<li class="current" ></li>';
        }
        return '<li id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).
                ($cellContent==null?'mask':'').'">'.$cellContent.'</li>';
    }
     
     //create navigation
    
    private function _createNavi(){
         
        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
         
        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
         
        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
         
        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;
         
        return
            '<div class="header">'.
                '<a class="prev" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'">Prev</a>'.
                    '<span class="title">'.date('Y M',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
                '<a class="next" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">Next</a>'.
            '</div>';
    }
         
    
    // create calendar week labels
   
    private function _createLabels(){  
                 
        $content='';
         
        foreach($this->dayLabels as $index=>$label){
             
            $content.='<li class="'.($label==6?'end title':'start title').' title">'.$label.'</li>';
 
        }
         
        return $content;
    }
     
    //calculate number of weeks in a particular month
    
    private function _weeksInMonth($month=null,$year=null){
         
        if( null==($year) ) {
            $year =  date("Y",time()); 
        }
         
        if(null==($month)) {
            $month = date("m",time());
        }
         
        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month,$year);
         
        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
         
        $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
         
        $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));

        if($monthEndingDay<$monthStartDay){
             
            $numOfweeks++;
         
        }
        return $numOfweeks;
    }
 
    //calculate number of days in a particular month
    
    private function _daysInMonth($month=null,$year=null){
         
        if(null==($year))
            $year =  date("Y",time()); 

 
        if(null==($month))
            $month = date("m",time());

        return date('t',strtotime($year.'-'.$month.'-01'));
    }



}