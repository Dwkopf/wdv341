// javascript functions for recipe project check for this now

<script>
function checkImage() { 

    var xmlhttp = new XMLHttpRequest();

    // xmlhttp.onreadystatechange=function(){
    //     if (xmlhttp.readyState==4 && xmlhttp.status==200)
    //     {
    //     //document.getElementById("myDiv").innerHTML = xmlhttp.responseText;
    //     }
    // }
    // xmlhttp.open("GET","checkImg.php",true);
    
    // xmlhttp.send();


    let fileInput =  
        document.getElementById('fileToUpload');   
    console.log(fileInput.value);   
    let filePath = fileInput.value; 
    // Allowing file types
    let allowedExtensions =  
            /(\.jpg|\.jpeg|\.png|\.gif)$/i; 
      
    if (!allowedExtensions.exec(filePath)) { 
        alert('Invalid file type'); 
        fileInput.value = ''; 
        return false; 
    }  
    else  
    {
        let file = fileInput.files[0];    
        alert(`File name: ${file.name}`+" has been approved."); // e.g my.png
        console.log(fileInput.files);
        
        var reader = new FileReader(); 
        reader.onload = function(e) { 
            document.getElementById( 
                'imagePreview').innerHTML =  
                '<img src="' + e.target.result 
                + '"/>'; 
        };              
        reader.readAsDataURL(fileInput.files[0]); 
        
        return true;
      }   
} 




function addImageSelector () {  // if upload img, display file uploader
    if (document.querySelector("#recipeImage").value=='y')
        document.querySelector("#getImage").style.display="block"
    else    
        document.querySelector("#getImage").style.display="none"
}





function addARecipe () {
    //alert("add recipe");
    // set newRecipe and ingredients to 0
    newRecipe= {};
    numOfIng =0;
    numSteps=0;

    if (document.querySelector("#recipeForm").style.opacity==0) {  // transform form in and out
            document.querySelector("#recipeForm").style.opacity=1;
            document.querySelector("#recipeForm").style.height="auto";
            document.querySelector("#recipeForm").style.transform= "translateY(400px)";
            document.querySelector("#recipeForm").style.zIndex=2;
    }
    else {
        document.querySelector("#recipeForm").style.opacity=0;
        document.querySelector("#recipeForm").style.height=0;
        document.querySelector("#recipeForm").style.transform= "translateY(-400px)";
        document.querySelector("#recipeForm").style.zIndex=-2;
    }            
}




function addIngredient() {
    // if amount is a number and string not empty
    if (document.querySelector("#ingredient").value !="") {
        if (numOfIng == 0)   {   // if first ingredient 
            document.querySelector("#deleteIng").style.display="inline-block";
            document.querySelector("#removeIngItem").style.display="inline-block";
            newRecipe.ingredients = [document.querySelector("#ingredient").value];
            document.querySelector("#ingredientList+p").innerHTML="Next ingredient: <input type ='text' name='ingredient' id='ingredient'></input>";}
        else // push onto array
            newRecipe.ingredients.push(" " + document.querySelector("#ingredient").value);

        document.querySelector("#ingredient").value="";  // reset text box
        numOfIng +=1;       // increment the ingredients
        
        let ingSoFar="";
        for (i=1;i<=numOfIng;i++) {
            ingSoFar+=`${i} : ${newRecipe.ingredients[i-1]} <br>`;  // backtick form
        }
        document.querySelector("#ingredientList").innerHTML=ingSoFar; // list the ingredients so far

        let result = document.querySelector("#removeIngItem");    // for removing steps, create the dropdown list
        let option = document.createElement("option");
        option.value = numOfIng;
        option.text = newRecipe.ingredients[numOfIng-1];
        result.appendChild(option); 
        
        //alert("function end back to form ingredient #: "+numOfIng);   
       
    }
}




// Add steps to recipe
function addAStep() {
    if (document.querySelector("#step").value != "") {
        if (numSteps == 0) {
            document.querySelector("#deleteStep").style.display="inline-block"; // turn on if there are steps
            document.querySelector("#removeItems").style.display="inline-block";
            newRecipe.instructions=[document.querySelector("#step").value];
            document.querySelector("#instructionList+p").innerHTML="Next step: <input type ='text' name='step' id='step'></input>";}
        else 
            newRecipe.instructions.push(" "+document.querySelector("#step").value);

        numSteps +=1;
        document.querySelector("#step").value="";   // reset text box
        let instructionSet = "";
        for (i=1;i<=numSteps;i++) {         
            instructionSet += i + ": " + newRecipe.instructions[i-1] + "<br>"; // build the display
        }
        document.querySelector("#instructionList").innerHTML=instructionSet; // list the instructions so far

        let result = document.querySelector("#removeItems");    // for removing steps, create the dropdown list
        let option = document.createElement("option");
        option.value = numSteps;
        option.text = numSteps;
        result.appendChild(option);   
    }
}




function removeStep() {
    let x = document.querySelector("#removeItems").value;
    let result = document.querySelector("#removeItems");
    list = newRecipe.instructions;
    list.splice(x-1,1); // removes step from array
    
    // element.outerHTML = '<select name="removeItems" id="removeItems"></select>';
    result.innerHTML = "<option>Choose step</option>";
    result.style.display="inline-block";
    console.log(newRecipe.instructions);
    numSteps -= 1;
    if (numSteps == 0)  {
        document.querySelector("#deleteStep").style.display="none";
        document.querySelector("#removeItems").style.display="none";
    }
    let instructionSet = "";
    for (i=1;i<=numSteps;i++) {
        let option = document.createElement("option");
        option.value = i;
        option.text = i;
        result.appendChild(option);   
        instructionSet += i + ": " + newRecipe.instructions[i-1] + "<br>";
    }
    document.querySelector("#instructionList").innerHTML=instructionSet; // list the instructions so far
}




function removeIng() {
    let x = document.querySelector("#removeIngItem").value;
    let result = document.querySelector("#removeIngItem");
    list = newRecipe.ingredients;
    list.splice(x-1,1); // removes step from array
    
    // element.outerHTML = '<select name="removeItems" id="removeItems"></select>';
    result.innerHTML = "<option>Choose ingredient</option>";
    result.style.display="inline-block";
    console.log(newRecipe.ingredients);
    numOfIng -= 1;
    if (numOfIng == 0)  {
        document.querySelector("#removeIngItem").style.display="none";
        document.querySelector("#deleteIng").style.display="none";
    }
    let instructionSet = "";
    for (i=1;i<=numOfIng;i++) {
        let option = document.createElement("option");
        option.value = i;
        option.text = newRecipe.ingredients[i-1];
        result.appendChild(option);   
        instructionSet += i + ": " + newRecipe.ingredients[i-1] + "<br>";
    }
    document.querySelector("#ingredientList").innerHTML=instructionSet; // list the instructions so far
}





function showRecipes(list)  {
    document.querySelector("#searchHere+input+span").innerHTML = "";
    let x = randomNumber(list);  // pick 3 random recipes from selection
    // display first recipe
    document.querySelector("#rec1 h3+h3").innerHTML=list[x[0]].name
    document.querySelector("#rec1 h3+h3+img").src=list[x[0]].image;
    document.querySelector("#rec1 h3+h3+img").alt="Recipe image";
    document.querySelector("#rec1 h3+h3+img+p button").outerHTML="<button>View</button>";
    document.querySelector("#rec1 h3+h3+img+p button").addEventListener('click', function(){
        displayFullRecipe(list[x[0]].name);
});
    if (list.length>1)  {
    // display second recipe
    document.querySelector("#rec2 h3+h3").innerHTML=list[x[1]].name
    document.querySelector("#rec2 h3+h3+img").src=list[x[1]].image;
    document.querySelector("#rec2 h3+h3+img").alt="Recipe image";
    document.querySelector("#rec2 h3+h3+img+p button").outerHTML="<button>View</button>";
    document.querySelector("#rec2 h3+h3+img+p button").addEventListener('click', function(){
        displayFullRecipe(list[x[1]].name);
});
    if (list.length>2)  {
    // display third recipe
    document.querySelector("#rec3 h3+h3").innerHTML=list[x[2]].name
    document.querySelector("#rec3 h3+h3+img").src=list[x[2]].image;
    document.querySelector("#rec3 h3+h3+img").alt="Recipe image";
    document.querySelector("#rec3 h3+h3+img+p button").outerHTML="<button>View</button>";
    document.querySelector("#rec3 h3+h3+img+p button").addEventListener('click', function(){
        displayFullRecipe(list[x[2]].name);
}); 
}}}




function searchRecipe()     {
    closeFullRecipe();
    
    <?php

try {
  
  require "recipeDBconnect.php";	//CONNECT to the database
  
  //Create the SQL command string
  $sql = "SELECT * FROM recipe WHERE rName LIKE '%q%'"; 

  //PREPARE the SQL statement
  $stmt = $conn->prepare($sql);
  
  //EXECUTE the prepared statement
  $stmt->execute();		
  //Prepared statement result will deliver an associative array
     $stmt->setFetchMode(PDO::FETCH_ASSOC);
    // $result =$stmt->fetchAll(PDO::FETCH_COLUMN, 'product_name');
}

catch(PDOException $e)
{
  $message = "There has been a problem. The system administrator has been contacted. Please try again later.";

  error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
  error_log($e->getLine());
  error_log(var_dump(debug_backtrace()));

  //Clean up any variables or connections that have been left hanging by this error.		

    //header('Location: files/505_error_response_page.php');	//sends control to a User friendly page					
}

?>


    // let searchFor = document.querySelector("#searchHere").value.toLowerCase();
    // let result = [];
    // let name = "";
    // for (i=0;i<recipeList.length;i++)  {
    //     name=recipeList[i].name.toLowerCase();
    //     if (name.indexOf(searchFor)!=-1)
    //         result.push(recipeList[i]);
    // }console.log(result.length);
    // if (result.length >0)
    //     showRecipes(result);
    // else document.querySelector("#searchHere+input+span").innerHTML = "<h3>Sorry "+searchFor+" Not found</h3>";
}




function displayRecipe() {
    
    if (document.querySelector("#viewRecipes").value=="Magic")  
        showRecipes(recipeList);
    else if (document.querySelector("#viewRecipes").value=="Drink") 
        showRecipes(drinkList);
        else if (document.querySelector("#viewRecipes").value=="Appetizer")
        showRecipes(appList); 
            else if (document.querySelector("#viewRecipes").value=="Entree")
            showRecipes(entreeList); 
                else if (document.querySelector("#viewRecipes").value=="Dessert")
                showRecipes(dessertList); 
                    else showRecipes(customerRecipes);
}




function displayFullRecipe(name)  { 
    document.querySelector("#disRecipes").style.display="none";
    document.querySelector(".container1").style.opacity=1;
    document.querySelector(".container1").style.height="auto";
    document.querySelector(".container1").style.transform="translate(1000px,-600px)";
    document.querySelector(".container1").style.zIndex=5;
    
    i=0;
    let multiplier =1;
    let recipeDetails= new Recipe;
    while(i<recipeList.length && recipeList[i].name!=name)  // check recipeList for recipe
        i++;
    if (i<recipeList.length)   {
        recipeDetails = recipeList[i];
        console.log(recipeDetails.name);}
    else {      // check customerRecipes for recipe
        i=0;
        while (i<customerRecipes.length && customerRecipes[i].name!=name) {
            i++;}
        recipeDetails = (customerRecipes[i]);
        console.log(recipeDetails.name);
    }
    document.querySelector("#details h1").innerHTML=recipeDetails.name;
    document.querySelector("#details h1+img").src=recipeDetails.image;
    document.querySelector("#details h1+img+h3").innerHTML="Servings: " +recipeDetails.serves;
    document.querySelector("#details h1+img+h3+h3").innerHTML="Preparation Time: " + recipeDetails.preparationTime;
    document.querySelector("#details h1+img+h3+h3+h3").innerHTML="Cook Time: " + recipeDetails.cookTime;

    let unit=0;
    let temp ="";
    let ingButton= document.createElement("p");
    ingButton.innerHTML="<button>Ingredient List</button>";// create button to list ingredients
    ingButton.addEventListener('click', function(){
        if (ingButton.innerHTML=="<button>Ingredient List</button>")   { // list ingredients on button click
            if (multiplier == 1) 
                for (i=1;i<=recipeDetails.ingredients.length;i++) {  console.log(recipeDetails.ingredients.length);
                    ingButton.innerHTML += "<p>"+i+": "+recipeDetails.ingredients[i-1]+"</p>";
                }
            else {      // multiplier in effect
                for (i=1;i<recipeDetails.ingredients.length;i++) { console.log(recipeDetails.ingredients.length);
                    if (recipeDetails.ingredients[i-1].indexOf(".")==-1)  {  // no decimal number to adjust
                        unit = parseInt(recipeDetails.ingredients[i-1]);
                        if ((multiplier*unit)%1!=0) // if its not an even multiple limit decimals
                            temp = recipeDetails.ingredients[i-1].replace(unit,(multiplier*unit).toFixed(2));
                        else temp = recipeDetails.ingredients[i-1].replace(unit,(multiplier*unit)); // else dont include the .00
                        ingButton.innerHTML+= "<p>"+i+": "+temp+"</p>";}
                    else {      // there is a decimal #
                        unit = recipeDetails.ingredients[i-1].slice(0,3);
                        temp = eval(unit*multiplier);
                        unit = recipeDetails.ingredients[i-1].slice(3,400);
                        ingButton.innerHTML+= "<p>"+i+": "+temp.toFixed(2)+unit+"</p>";
                    }
                }
                ingButton.innerHTML += "<p>"+i+": "+recipeDetails.ingredients[i-1]+"</p>";
            }
            }
        else ingButton.innerHTML="<button>Ingredient List</button>";
    });
    document.querySelector("#details h1+img+h3+h3+h3").appendChild(ingButton);  

    let instButton= document.createElement("p");
    instButton.innerHTML="<button>Instruction List</button>";// create button to list instructions
    instButton.addEventListener('click', function(){
        if (instButton.innerHTML=="<button>Instruction List</button>")  {  // list instructions on button click
            if (multiplier !=1)
                instButton.innerHTML += "<p>Note: Please adjust these ingredients by your multiplier of "+multiplier.toFixed(1)+" to get the correct amount.</p>";
            for (i=1;i<=recipeDetails.instructions.length;i++) {        // build instruction set
                instButton.innerHTML += "<p>"+i+": "+recipeDetails.instructions[i-1]+"</p>";   // Theoretical instruction set multiplier
            }   }                                                                           // mods go here, I give up
        else instButton.innerHTML="<button>Instruction List</button>";
    });
    document.querySelector("#details h1+img+h3+h3+h3").appendChild(instButton);  

    let adjustButton= document.createElement("p");
    adjustButton.innerHTML="<button>Adjust servings</button>";// create button to adjust servings
    adjustButton.addEventListener('click', function(){
        multiplier = prompt("How many servings do you need?");
        //console.log(multiplier);
            
        if (parseInt(multiplier))    {
            multiplier = parseInt(multiplier);
            multiplier = multiplier/recipeDetails.serves;
            alert("Got it. Please refresh ingredients.");}
        else multiplier=1;
            
        });
    document.querySelector("#details h1+img+h3+h3+h3").appendChild(adjustButton);  

    let closeButton = document.createElement("p");
    closeButton.innerHTML="<button>Close Recipe</button>";// create button to close recipe details
    document.querySelector("#details h1+img+h3+h3+h3").appendChild(closeButton);  
    closeButton.addEventListener('click', closeFullRecipe);
}  



function closeFullRecipe() {   //close displayFullRecipe page
    document.querySelector("#disRecipes").style.display="grid";
    document.querySelector(".container1").style.opacity=0;
    document.querySelector(".container1").style.height=0;
    document.querySelector(".container1").style.transform="translate(-1000px,600px)";
    document.querySelector(".container1").style.zIndex=-5;
}




function randomNumber(inNum) {      // get up to 3 different recipes
    let x = Math.floor(Math.random()* inNum.length);
    let y = Math.floor(Math.random()* inNum.length);
    let z = Math.floor(Math.random()* inNum.length);
    if (inNum.length>1) 
        while (x==y)
            y = Math.floor(Math.random()* inNum.length);
    if (inNum.length>2) 
        while (z==x||z==y)
            z = Math.floor(Math.random()* inNum.length);
    return 	[x,y,z];//random number from 1 to inNum.length	
}




  function validForm() {
        //return true;
        let message ="";
        if (document.querySelector("#category").value !="Select") 
            if (parseInt(document.querySelector("#servings").value)) 
                if (numOfIng != 0) 
                    if (numSteps != 0)
                        if (document.querySelector("#recipeName").value != "")
                            if (document.querySelector("#prepTime").value != "")
                                if (document.querySelector("#cookTime").value != "")
                                    return true;
                                else message += "Please enter cook time for recipe";
                            else message += "Please enter preparation time for the recipe";
                        else message += "Please enter a name for the recipe";
                    else message+= "Please enter instructions for the recipe";
                else message += "Please enter ingredients for the recipe";
            else message += "Please input the serving size for recipe";
        else message += "Please select category for recipe";
        document.querySelector("#errorMsg").innerHTML = message;
        return false;
}




function submitRecipe() {
    if (validForm())  {     // checks that everything is filled out
        newRecipe.name = document.querySelector("#recipeName").value;
        if (document.querySelector("#recipeImage").value == "n" || // check for image
            document.querySelector("#recipeImage").value == "s")
            newRecipe.image = "images/notAvailable.jpg";
        else {
            let x = Math.floor(Math.random()* recipeList.length);  // pick a random recipe image, can't find the upload image
            newRecipe.image = recipeList[x].image;
        }
        newRecipe.category = document.querySelector("#category").value;     // assign values to newRecipe
        newRecipe.serves = parseInt(document.querySelector("#servings").value);
        newRecipe.preparationTime = document.querySelector("#prepTime").value;
        newRecipe.cookTime = document.querySelector("#cookTime").value;
        
        customerRecipes.push(newRecipe);

        localStorage.setItem("recipes", JSON.stringify(customerRecipes));
        
        console.log(customerRecipes);
        document.querySelector("#myRecipe").style.display="block";
    }
}

/*
function fixInst(list){
        instList=[];
        matches=[];
        for (i=0;i<list.length;i++) {
            matches = list.match(/[-]?[0-9]+[,.]?[0-9]*([\/][0-9]+[,.]?[0-9]*)* /g);  a start on inst set mods, delete space between * and /
            for (m=0;m<matches.length;m++){
                if (matches[m].indexOf(".")!=-1)
                    instList=
            }
        }} */
    </script>