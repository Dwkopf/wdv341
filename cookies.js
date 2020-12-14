
// This is a 'generic' function that will add a value or cookie to the document
// It uses name-value pairs as input parameters to the function
// 'tag' is the name of the data that you wish to store
// 'value' contains the contents that are associated with the name of the data

function createFile() {
    localStorage.firstname=document.querySelector("#firstName").value;
    localStorage.lastname=document.querySelector("#lastName").value;
    localStorage.email=document.querySelector("#email").value;
    console.log(localStorage.getItem("lastName"));
}

function addAuthentication() {
    let authentic = new Date();
    authentic.setTime(authentic.getTime()+(1000*60*60*24*7));
    let expireString="expires="+authentic.toGMTString();
    document.cookie="authentication =Authenticated Page until "+authentic.toGMTString()+ "; expires=" + expireString + ";"
    console.log(expireString);
    console.log(document.cookie)
}

  function addCookie(tag, value) {
    var expireDate = new Date()
    var expireString = "";
    expireDate.setTime(expireDate.getTime() + (1000 * 60 * 60 * 24 * 365) );
    expireString = "expires="+ expireDate.toGMTString();
    console.log(expireString);
    document.cookie = tag + "=" + escape(value) + ";" + expireString + ";"
  }


// This is a 'generic' function that will look for a specific piece of information 
// in a cookie and return its value.  
// The 'name' of the function is passed to the function using the 'tag' parameter
// 'tag' contains the name of the name-value pair that you wish to find
// This function will return the value associated with the name requested.

  function getCookie(tag) {
    var value = null
    var myCookie = document.cookie + ";"
    var findTag = tag + "=";
    var endPos;
    if (myCookie.length > 0 ) {
      var beginPos = myCookie.indexOf(findTag);
      if (beginPos != -1) {
        beginPos = beginPos + findTag.length;
        endPos = myCookie.indexOf(";", beginPos);
        if (endPos == -1)
          endPos = myCookie.length
        value = unescape(myCookie.substring(beginPos, endPos))
      }
    } 
   return value   
  } 


// This is a 'generic' function tht will delete the cookie.  This is done by setting 
// the expiration date of the cookie to yesterday.
// 'tag' contains the name of the cookie element that you wish to delete.

  function deleteCookie(tag) {
console.log("Deleting " + tag + " cookie");
    var Yesterday = 24 * 60 * 60 * 1000;
    var expireDate = new Date();
    expireDate.setTime (expireDate.getTime() - Yesterday);
    document.cookie = tag + "=nothing; expires=" + expireDate.toGMTString();
  }

//END OF COOKIE FUNCTIONS



    

    function deleteCookies() { 
          let allCookies = document.cookie.split(';'); 
console.log(document.cookie);
      // The "expire" attribute of every cookie is  
      //Set to "Thu, 01 Jan 1970 00:00:00 GMT" 
      for (var i = 0; i < allCookies.length; i++) {
        let name=allCookies[i].split("=");
        deleteCookie(name[0]);
          }    
    }



