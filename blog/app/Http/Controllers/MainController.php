<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MainFormRequest;

class MainController extends Controller
{
    public function match(MainFormRequest $request)
	{
		/*
			Receiving zip codes of agents			
		*/
		$z1 = $request->get('z1');//zip code of Agent 1
		$z2 = $request->get('z2');//zip code of Agent 2
		
		/*
			Instead of search the coordinates related to these zip codes, 
			(for example, using this google api service https://maps.googleapis.com/maps/api/geocode/json?components=postal_code:98456&key=AIzaSyDC7yaWrM9wkoATWecfqQo8RU7HKgVqb0c
			to get real coordinates through the output JSON obtained, or another service, or a great database with zip codes related to coordinates)
			I'm going to simplify this test to
			generate random latitude and longitude for each zip code, since zip codes are not related to distance and its location coordinates must be used.
		*/
		
		//Validating is zip codes are the same
		if($z1 != $z2){
			$latz1=90/rand (-900000000, 900000000);//latitude of Agent 1
			$latz2=90/rand (-900000000, 900000000);//latitude of Agent 2
			$lonz1=180/rand (1, 1800000000);//longitude of Agent 1
			$lonz2=180/rand (1, 1800000000);//longitude of Agent 2
		}else{
			$latz1=90/rand (-900000000, 900000000);//latitude of Agent 1
			$latz2=$latz1;//latitude of Agent 2
			$lonz1=180/rand (1, 1800000000);//longitude of Agent 1
			$lonz2=$lonz1;//longitude of Agent 2
		}
		
		$contactsForAgents = array(); //array to register the splittedlist of contact per agent
		$row = 1; 
		$agent=1;
		$countAgent1=0;
		$countAgent2=0;
		/*
			To split contact I could make it in 3 forms:
			1. Counting the contacts listed and dividing the list in same quantity to each agent, taking always the closest to their locations
			2. Trying the agents have similar distances accumulated, maybe one of the agents will have more contacts closer to its location, and the other will have contacts closer to him but far in relation to others. One of them could have more than the other. 	
			3. Just spliting the list by the closer distance between both agents locations. One of them could have more than the other. 	
			
			I'm going to mix 1 and 3. With 3, i'm goint to obtain the list, but then I'm going to give to each agent the same quantity of contacts with closest distance criteria. 
		*/
		$flag=1;
		$fp = file('contactslist.csv');
		$totalRows= count($fp);
		$difA1A2=0;
		$i=0;
		$j=0;
		
		//Reading lines of csv of contacts list. (instead of using any database)
		if (($contactsList = fopen("contactslist.csv", "r")) !== FALSE) {
			while (($contact = fgetcsv($contactsList, 1000, ",")) !== FALSE) {
				$columns = count($contact);
				
				//Taking ("generating") coordinates of this zip code
				$lon=180/rand (1, 1800000000);
				$lat=90/rand (-900000000, 900000000);
				
				/*
					Calculating distance between agents and contact to assign contact to the closer agent.
					I'm going to use Pitagoras to solve distance between 2 points, I consider the difference 
					between the result using this formula and the exact value (error) is negligible.
				*/
				
				$distanceFromAgent1 = sqrt(pow(($lon - $lonz1),2) + pow(($lat - $latz1),2));
				$distanceFromAgent2 = sqrt(pow(($lon - $lonz2),2) + pow(($lat - $latz2),2));
				//echo $distanceFromAgent1 . $distanceFromAgent2 ."<br>";
				//Comparing distance to assign contact to agent
				if($distanceFromAgent1 <= $distanceFromAgent2){	
					$difA1A2= $distanceFromAgent2 - $distanceFromAgent1;
					$countAgent1++;//Counting contacts assigned to agent 1
					$agent = 1;
					if($distanceFromAgent1 == $distanceFromAgent2 && $countAgent2 == 0 && $countAgent1>round($totalRows/2,0)){//if z1 and z2 are equals and agent 1 is full, then assign the rest to agent 2
						$agent = 2;
						$countAgent1--;
					}
				}else{							
					$difA1A2= $distanceFromAgent1 - $distanceFromAgent2;
					$countAgent2++;//Counting contacts assigned to agent 1
					$agent = 2;
				}
				//Adding assignation to list
				array_push($contactsForAgents,array("AgentID"=>$agent, "ContactName"=>$contact[0] , "ZipCode"=>$contact[$columns-1] , "DifA1A2"=>$difA1A2));		
			}
			fclose($contactsList);//Closing csv
			
			/*
				If agents doesn't have the "same" quantity of contacts, 
				I take those contacts who are also closer to the agent with 
				less contacts and reassign them to this agent
			*/
			if($countAgent1 != round($totalRows/2,0)){
				if($countAgent1 < $countAgent2){	//If agent 1 has less contacts than agent 2
					//Order by difference between distances of agents 1 and 2 with the contact
					foreach ($contactsForAgents as $key => $row) {						   
						$difArray[$key]  = $row['DifA1A2'];
					}
					array_multisort($difArray, SORT_ASC, $contactsForAgents);
					//passing through rows to change those contacts with minor difference from agent 2 to the agent 1
					foreach($contactsForAgents as &$value){
						if($value['AgentID'] == 2) {							
							$i++;
							$value['AgentID'] = 1;
							$countAgent2--;
							$countAgent1++;
						}
						//if both have the "same" quantity, the list is ready
						if($countAgent2 == $countAgent1 || $countAgent1== round($totalRows/2,0)) break;						
					}
				}else{	//If agent 2 has less contacts than agent 1
					//Order by difference between distances of agents 1 and 2 with the contact
					foreach ($contactsForAgents as $key => $row) {						   
						$difArray[$key]  = $row['DifA1A2'];
					}
					array_multisort($difArray, SORT_ASC, $contactsForAgents);
					//passing through rows to change those contacts with minor difference from agent 1 to the agent 2
					foreach($contactsForAgents as &$value){
						if($value['AgentID'] == 1){ 
							$j++;
							$value['AgentID'] = 2;
							$countAgent2++;
							$countAgent1--;
						}
						//if both have the "same" quantity, the list is ready
						if($countAgent2 == $countAgent1 || $countAgent2 == round($totalRows/2,0)) break;						
					}									
				}
			}
			/*
				Finally, order the list by Agent ID
			*/
			foreach ($contactsForAgents as $key => $row) {						   
				$difArray[$key]  = $row['AgentID'];
			}
			array_multisort($difArray, SORT_ASC, $contactsForAgents);
			//print_r($contactsForAgents);
		}		
		
        return view('welcome')->with('contactsForAgents', ($contactsForAgents));
    }

}
