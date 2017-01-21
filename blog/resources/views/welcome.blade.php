<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #444;
                font-family: 'Arial', sans-serif;
                font-weight: 100;
                
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref">
           
            <div class="content">
                <div class="">
                    Laravel Test - Spliting Contacts to agents based on zip codes through its location
                </div>			
				<ul>
				  @foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				  @endforeach
				</ul>		
				
				<form action="http://<?=$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]?>match">
					<div class="table">
						<div class="fields-container">
							<div class="field input-text">
								<label><u>ZipCode 1</u></label>								
								<input type="text" name="z1" size='12' maxlength='10' required value="<?php if(isset($_GET['z1'])) echo $_GET['z1']?>" />
							</div>
							<div class="field input-text">
								<label><u>ZipCode 2</u></label>							
								<input type="text" name="z2" size='12' maxlength='10' required  value="<?php if(isset($_GET['z2'])) echo $_GET['z2']?>" />
							</div>
						</div>
					</div>				
					<div class="table">
						<div class="edit-fields">
							<div class="inline save"><input name="send" id="btn_sav" type="submit" value ="MATCH" class="redondeoboton"/></div>
						</div>
					</div>
				</form>
				@if(isset($contactsForAgents))
				<div class="m-b-md"></div> 
				<table width="100%" border="3" align="center" class="Estilo4" >
				  <tr>
					<th scope="col" align="center">AgentID</th>
					<th scope="col" align="center">Contact Name</th>
					<th scope="col" align="center">Contact Zip Code</th>
				  </tr>
					@foreach($contactsForAgents as $contact)
				  <tr>					
					<td scope="col" align="center">{{ $contact['AgentID'] }}</td>  
					<td scope="col" align="center">{{ $contact['ContactName'] }}</td>  
					<td scope="col" align="center">{{ $contact['ZipCode'] }}</td>  
				  </tr>
					@endforeach 
				</table>	
				@endif 				
            </div>
			
			
        </div>
    </body>
</html>
