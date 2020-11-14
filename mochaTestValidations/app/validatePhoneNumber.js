// javascript file

// Test Plan 

// Input 				Expected Output

// 5-5555				invalid
// 123a56789			invalid
// 0000				    invalid
// 1234567890			valid
// 555-555-5555		    valid
// -,.+!@ =/			invalid
// 12345				invalid
// 12345-6789			invalid 
// ""					invalid	
// null				    invalid
// undefined			invalid
// 06001				invalid





var validatePhoneNumber = function(inValue){
	inValue += "";	//turns all inValues into strings

    var phoneno = /^\d{10}$/;
    var phoneno2 = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
    if (phoneno.test(inValue)||phoneno2.test(inValue))
      return true;
    else
    //alert("message");
        return false;
        
}

module.exports =validatePhoneNumber