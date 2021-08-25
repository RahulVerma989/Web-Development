<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <link rel='stylesheet' href='/public/css/FormControl.css?t=<?php echo time();?>'>
    <title>Rahul Verma - Coding Challenge</title>

</head>
<body>
    <h1>Coding Challenge</h1>
    <div id='Forms'>        
        <div class='CreatedForms'>
            <div id='FieldsAdded'>
                <span class='user'><b>User Name</b></span>
            </div>
        </div>
        <div id='ControlForm'>
            <? form_open('form');?>

                <label for='label'>Label</label>
                <input type='text' name='label' id='label' minlength=2 maxlength=20 required/>


                <label for='InputType'>Input Field Type</label>
                <select name='InputType' id='InputType' required>
                    <option disabled='disabled' selected>Select Input Field type</option>
                    <option value='text'>text</option>
                    <option value='tel'>Phone Number</option>
                </select>

                <label for='Validation'>Validation Type</label>
                <select name='Validation' id='Validation' required>
                    <option disabled='disabled' selected>Please select validation type</option>
                    <option value='required'>required</option>
                    <option value='optional'>optional</option>
                </select>

                <label for='MinLength'>Min. Length</label>
                <input type='number' name='MinLength' min=0 id='MinLength' required/>
                
                <label for='MaxLength'>Max. Length</label>
                <input type='number' name='MaxLength' min=0 id='MaxLength' required/> 

                <label for='ErrorMessage'>Error Message</label>
                <input type='text' name='ErrorMessage' minlemgth=4 id='ErrorMessage' value='Invalid input!'/>

                <label for='Users'>Select Users</label>
                <select name='Users' id='Users' required>
                </select>

                <input type='button' name='AddField' value='Add field'>
                <input type='button' name='CreateForm' value='Create From'>

            </form>
        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>

//getting users data using ajax and storing it in the control from
$.ajax({
    url:'https://jsonplaceholder.typicode.com/users',
    type:'GET',
    success:function(data){
        // console.log(data);
        $('#ControlForm #Users').empty();
        $('#ControlForm #Users').append("<option disabled='disabled' selected>Please select a user</option>");
        for(var i = 0; i < data.length; i++)
        {
            $('#ControlForm #Users').append("<option value='"+data[i]['id']+"'>"+data[i]['name']+"</option>");
        }
    },
    error: function (xhr, exception) {
            var msg = "";
            if (xhr.status === 0) {
                msg = "Not connect.\n Verify Network." + xhr.responseText;
            } else if (xhr.status == 404) {
                msg = "Requested page not found. [404]" + xhr.responseText;
            } else if (xhr.status == 500) {
                msg = "Internal Server Error [500]." +  xhr.responseText;
            } else if (exception === "parsererror") {
                msg = "Requested JSON parse failed.";
            } else if (exception === "timeout") {
                msg = "Time out error." + xhr.responseText;
            } else if (exception === "abort") {
                msg = "Ajax request aborted.";
            } else {
                msg = "Error:" + xhr.status + " " + xhr.responseText;
            }
           
            $('#ControlForm .StatusReport').html(msg);
            $('#ControlForm .StatusReport').show();
        }
});

//changing the user name on change the user in control from
$('#ControlForm #Users').on('change',function(){
    $UserName = $(this).children(':selected').html();
    $UserId = $(this).children(':selected').val();
    // console.log($name);
    $('#FieldsAdded .user b').html($UserName);
    $('#FieldsAdded .user b').attr('UserId',$UserId);
});

//for rewiewing created input fields data
$('.CreatedForms #FieldsAdded').on('click','.field',function(){

    $work = $(this).attr('class');
    switch($work)
    {
        case 'field':{
            $data = JSON.parse($(this).attr('data'));
            $label = $(this).children('.LabelName').html();

            $('#ControlForm #label').val($label);
            $('#ControlForm #Validation').val($data['Validation']);
            $('#ControlForm #MinLength').val($data['MinLength']);
            $('#ControlForm #MaxLength').val($data['MaxLength']);
            $('#ControlForm #ErrorMessage').val($data['ErrorMessage']);
        }break;
    }
});

//for deleting created input fields 
$('.CreatedForms #FieldsAdded').on('click','span b.fa-times-circle',function(){
    $(this).parent('span').remove();
});

//adding fields and creating from
$('#ControlForm input[type=button]').on('click',function(){
    //first we will identify which button is pressed by the user 
    $work = $(this).attr('name');

    //creating an array to store the success or failure or any 
    //other messages to display in the control
    $error = [];

    if($('#label:valid').length)
    {
        if($('#InputType option:selected').prop('disabled') == false)
        {
            //if selected input type field is selected by the user 
            if($('#Validation option:selected').prop('disabled') == false)
            {
                //if validation function is also selected
                if($('#MinLength:valid').length)
                {
                    $MinLength = parseInt($('#MinLength').val());

                    if($('#MaxLength:valid').length)
                    {
                        $MaxLength = parseInt($('#MaxLength').val());

                        // console.log(typeof($Maxlength));

                        // console.log('Max = '+$MaxLength+', '+'Min = '+$MinLength);
                        if($MinLength < $MaxLength)
                        {
                            if($('#ErrorMessage').val().length)
                            {
                                
                                if($('#ControlForm #Users').children(':selected').attr('disabled') == 'disabled')
                                {
                                    //if user has not selected the username
                                    $error.push({Message:'Please select a user',type:'false'});   
                                }
                                else
                                {
                                    $UserName = $('#ControlForm #Users').children('option:selected').html();
                                    $UserId = $('#ControlForm #Users').children('option:selected').val();
                                    $Label = $('#label').val();
                                    $FieldType = $('#InputType option:selected').val();
                                    $validation = $('#Validation option:selected').val();
                                    $ErrorMessage = $('#ErrorMessage').val();

                                    
                                    switch($work)
                                    {
                                        case 'AddField':{

                                            //if user wants to add fields again then show to field section and destroy the previously created form
                                            if($('#FieldsAdded').is(':hidden'))
                                            {                                                
                                                $('#FieldsAdded').show();
                                                $('#Forms .CreatedForms form').remove();
                                            }
                                            
                                            //if user wants to add a field for its from
                                            $('#FieldsAdded').append(`<span class='field' data='{"FieldType":"`+$FieldType+`","Validation":"`+$validation+`","MinLength":`+$MinLength+`,"MaxLength":`+$MaxLength+`,"ErrorMessage":"`+$ErrorMessage+`"}'><b class='LabelName'>`+$Label+`</b><b class='fa fa-times-circle'></b></span>`);
                                            $error.push({Message:'Field Added',type:'true'}); 
                                        }break;
                                        case 'CreateForm':{
                                            
                                            $UserName = $('#FieldsAdded .user b').html();
                                            $UserId = $('#FieldsAdded .user b').attr('userid');

                                            $form = "<form action='' method='POST' userid='"+$UserId+"'>";
                                            $form += "<h5>"+$UserName+"</h5>";
                                            
                                            //check if any input field is created by the user or not
                                            if($('#Forms .CreatedForms #FieldsAdded').children('.field').length)
                                            {
                                                //create an input field for each input field created
                                                    $('#Forms .CreatedForms #FieldsAdded').children('.field').each(function(){
                                                    $data = JSON.parse($(this).attr('data'));
                                                    $label = $(this).children('.LabelName').html();
                                                    
                                                    $validation = '';

                                                    if($data['Validation'] == 'required')
                                                    {
                                                        $validation = 'required';
                                                    }

                                                    $form += "<label for='"+$label+"'>"+$label+"</label>"; 
                                                    $form += "<input type='"+$data['FieldType']+"' name='"+$label+"' minlength='"+$data['MinLength']+"' maxlength='"+$data['MaxLength']+"' ErrorMessage='"+$data['ErrorMessage']+"' "+$validation+"/>";
                                                });

                                                $form += "<p class='StatusReport'></p>";
                                                $form += "<input type='button' value='submit' id='submit' name='submit'/>";
                                                $form += "</form>";                                                
                                                
                                                $('#Forms .CreatedForms').append($form);
                                                
                                                //hide the fields section once from is created
                                                $('#FieldsAdded .field').remove();
                                                $('#FieldsAdded').hide();
                                            }                                    
                                            else
                                            {
                                                $error.push({Message:'Create input fields first',type:'false'});
                                            }  

                                        }break;
                                    }
                                }
                            }
                            else
                            {
                                $error.push({Message:'Please type an error message',type:'false'});
                            }
                        }
                        else
                        {
                            $error.push({Message:'MPlease select a valid length',type:'false'});   
                        }
                    }
                    else
                    {
                        $error.push({Message:'Please enter the maximum length of this field',type:'false'});   
                    }

                }
                else
                {
                    $error.push({Message:'Please enter the minimum length of this field',type:'false'});   
                }
            }
            else
            {
                $error.push({Message:'Please select validation function for this field',type:'false'});   
            }
        }
        else
        {
            $error.push({Message:'Please select input field type',type:'false'});
        }
    }
    else
    {
        $error.push({Message:'Please enter field label',type:'false'});
    }

    //Removing any previous messages
    $('#ControlForm .StatusReport').empty();

    // console.log($error);
    // console.log($error.length);

    for($i = 0; $i < $error.length; $i++)
    {
        // console.log($error[$i]);
        if($error[$i]['type'] == 'false')
        {
            $("<p class='StatusReport FailureReport'>"+$error[$i]['Message']+"</p>").insertBefore($('#ControlForm input[name=AddField]'));
        }
        else
        if($error[$i]['type'] == 'true')
        {
            $("<p class='StatusReport SuccessReport'>"+$error[$i]['Message']+"</p>").insertBefore($('#ControlForm input[name=AddField]'));
        }
    }
    $('.StatusReport').show();
    VanishNotifications($('#ControlForm .StatusReport'));
});

//this function when called will hide the element
//passed in the argument
//after 3 seconds or 3000ms 
function VanishNotifications($elementToVanish)
{
    setTimeout(function(){
        $elementToVanish.remove();
    },3000);
}

function DisplayMessage($parent,$message,$messagetype)
{
    $NotificationBlock = $parent.children('.StatusReport');
    if($NotificationBlock.is(':hidden'))
    {
        $NotificationBlock.show();
    }

    //write the message
    $NotificationBlock.html($message);

    if($messagetype == 'true')
    {
        //if good message
        if($NotificationBlock.hasClass('FailureReport'))
        {
            $NotificationBlock.removeClass('FailureReport'); 
            $NotificationBlock.addClass('SuccessReport'); 
        }
        else
        if(!$NotificationBlock.hasClass('SuccessReport'))
        {
            //if doesnot has success message class
            $NotificationBlock.addClass('SuccessReport'); 
        }
    }
    else
    if($messagetype == 'false')
    {
        //if bad message
        if($NotificationBlock.hasClass('SuccessReport'))
        {
            $NotificationBlock.removeClass('SuccessReport'); 
            $NotificationBlock.addClass('FailureReport'); 
        }
        else
        if(!$NotificationBlock.hasClass('FailureReport'))
        {
            //if doesnot has success message class
            $NotificationBlock.addClass('FailureReport'); 
        }
    }
}

//check for errors in input fields on change
$('.CreatedForms').on('keyup','form input:not([type=button]), form select',function(){
   
    // console.log('changed');

    if($(this).is(':invalid'))
    {
        // if input is invalid
        //then show the error
        $error = $(this).attr('errormessage');
        DisplayMessage($(this).parent('form'),$error,'false');
    }
    else
    {
        if($(this).parent('form').children('.StatusReport').is(":visible"))
        {
            $(this).parent('form').children('.StatusReport').hide();
        }
    }

});

//on submitting the created form
$('.CreatedForms').on('click','form input#submit',function(){

    //number of input fields
    $fields = $('.CreatedForms form').children('input:not([id=submit]),select').length;
    $valid = 0;
    $ThisForm = $(this).parent('form');

    // console.log($fields);

    //checking is each field is valid or not

    $json = '{';
        
    $('.CreatedForms form').children('input:not([id=submit]), select').each(function(){

        //checking if each field is valid or not
        if($(this).is(':valid'))
        {
            $valid++;
            $name = $(this).attr('name');
            $value = $(this).val();
            $json += '"'+$name+'":"'+$value+'",';
        }
        else
        {
            //if input field is invalid 
            DisplayMessage($(this).parent('form'),$(this).attr('errormessage'),'false');
            // console.log('error');
        }
    });
    
    $json += '"userId":'+$(this).parent('form').attr('userid');
    $json += '}';
    
    if($fields == $valid)
    {
        //if form is valid then send data using ajax
        $.ajax({
            url:'https://jsonplaceholder.typicode.com/posts',
            data:$json,
            dataType:'json',
            success: function (data, status, xhr) {
                // console.log('status: ' + status );
                DisplayMessage($ThisForm,status,'true');
            },
            error: function (jqXhr, textStatus, errorMessage) {
                // console.log('Error : ' + errorMessage);
                DisplayMessage($ThisForm,errorMessage,'false');
            }
        });
    }
});


</script>
</body>
</html>