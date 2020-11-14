/*

Test Plan 

Input 				Expected Output

/                   -
<                   -
>                   -
'                   -
*/

var replaceSpecials =function(inStr)	{
    inStr += "";
    inStr = inStr.replace(/\'/g, '-');
    inStr = inStr.replace(/\</g, '-');
    inStr = inStr.replace(/\>/g, '-');
    inStr = inStr.replace(/\//g, '-');
        return true;
}

module.exports = replaceSpecials