/*

A form input field will accept a zip code...

Validate the zip code before accepting it into the data object.

What are the rules?

numeric
5 digits OR 9 digits 

Test Plan 

Input 				Expected Output

5-5555				invalid
Aa-aaaaa...			invalid
0000				false
00000				invalid
00000-0000			false 
-,.+!@ =/			invalid
12345				valid
12345-6789			valid 
""					invalid	
null				invalid
undefined			invalid
06001				valid


*/


var validateZipCode =function(inZip)	{
	
	//let zipPattern = /^\d{5}(-\d{4})?$/;	//FAILED
	
	if ( (inZip + "") === "00000" || (inZip+"") === "00000-0000" ) {
		return false;
	}
	
	let zipPattern = /^[0-9]{5}(?:-[0-9]{4})?$/;
	
	return zipPattern.test(inZip + "");

}

module.exports = validateZipCode
























