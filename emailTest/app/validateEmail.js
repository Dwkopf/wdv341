// javascript file

// Test Plan 

// Input 				Expected Output

// www.wilfail.com              invalid
// 5-5555				        invalid
// 123a56789			        invalid
// anythingwithoutan'at'symbol  invalid
// ""                           invalid
// anything with@               valid
// www.willsucceed@fail.com     valid

var validateEmail = function(inEmail)  {
    inEmail +="";
    if (inEmail.indexOf("@")!=-1)
        return true;
    else    
        return false;

}

module.exports =validateEmail

