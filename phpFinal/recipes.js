// javascript functions for recipe project check for this now

function quit() {
    window.location.href = "recipeLogin.php";
}

numOfIng =0;
numSteps=0;

function noenter() {
  return !(window.event && window.event.keyCode == 13); }


function checkImage() { 

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




function addIngredient(inRecipe) {
    // if amount is a number and string not empty
    if (document.querySelector("#ingredient").value !="") {
        if (numOfIng == 0)   {   // if first ingredient 
            document.querySelector("#deleteIng").style.display="inline-block";
            document.querySelector("#removeIngItem").style.display="inline-block";
            inRecipe.ingredients = [document.querySelector("#ingredient").value];
            document.querySelector("#ingredientList+p").innerHTML="Next ingredient: <input type ='text' name='ingredient' id='ingredient'></input>";}
        else // push onto array
        inRecipe.ingredients.push(" " + document.querySelector("#ingredient").value);

        document.querySelector("#ingredient").value="";  // reset text box
        numOfIng +=1;       // increment the ingredients
        
        let ingSoFar="";        // build display of ingredients
        for (i=1;i<=numOfIng;i++) {
            ingSoFar+=`${i} : ${inRecipe.ingredients[i-1]} <br>`;  // backtick form
        }
        document.querySelector("#ingredientList").innerHTML=ingSoFar; // list the ingredients so far
        document.querySelector("#ingredientArray").value = inRecipe.ingredients;

        let result = document.querySelector("#removeIngItem");    // for removing ingredients, create the dropdown list
        let option = document.createElement("option");
        option.value = numOfIng;
        option.text = inRecipe.ingredients[numOfIng-1];
        result.appendChild(option); 
        
    }
}




// Add steps to recipe
function addAStep(inRecipe) {
    if (document.querySelector("#step").value != "") {
        if (numSteps == 0) {
            document.querySelector("#deleteStep").style.display="inline-block"; // turn on if there are steps
            document.querySelector("#removeItems").style.display="inline-block";
            inRecipe.instructions=[document.querySelector("#step").value];
            document.querySelector("#instructionList+p").innerHTML="Next step: <input type ='text' name='step' id='step'></input>";}
        else 
            inRecipe.instructions.push(" "+document.querySelector("#step").value);

        numSteps +=1;
        document.querySelector("#step").value="";   // reset text box
        let instructionSet = "";
        for (i=1;i<=numSteps;i++) {         
            instructionSet += i + ": " + inRecipe.instructions[i-1] + "<br>"; // build the display
        }
        document.querySelector("#instructionList").innerHTML=instructionSet; // list the instructions so far
        document.querySelector("#instructionArray").value = inRecipe.instructions;

        let result = document.querySelector("#removeItems");    // for removing steps, create the dropdown list
        let option = document.createElement("option");
        option.value = numSteps;
        option.text = numSteps;
        result.appendChild(option);  
        
    }
}




function removeStep(inRecipe) {
    let x = document.querySelector("#removeItems").value;
    let result = document.querySelector("#removeItems");
    list = inRecipe.instructions;
    list.splice(x-1,1); // removes step from array
    
    // element.outerHTML = '<select name="removeItems" id="removeItems"></select>';
    result.innerHTML = "<option>Choose step</option>";
    result.style.display="inline-block";
    //console.log(newRecipe.instructions);
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
        instructionSet += i + ": " + inRecipe.instructions[i-1] + "<br>";
    }
    document.querySelector("#instructionArray").value = inRecipe.instructions;
    document.querySelector("#instructionList").innerHTML=instructionSet; // list the instructions so far
}




function removeIng(inRecipe) {
    
    let x = document.querySelector("#removeIngItem").value;
    let result = document.querySelector("#removeIngItem");
    list = inRecipe.ingredients;
    
    list.splice(x-1,1); // removes step from array
    
    // element.outerHTML = '<select name="removeItems" id="removeItems"></select>';
    result.innerHTML = "<option>Choose ingredient</option>";
    result.style.display="inline-block";
    
    numOfIng -= 1;
    if (numOfIng == 0)  {
        document.querySelector("#removeIngItem").style.display="none";
        document.querySelector("#deleteIng").style.display="none";
    }
    let instructionSet = "";
    for (i=1;i<=numOfIng;i++) {
        let option = document.createElement("option");
        option.value = i;
        option.text = inRecipe.ingredients[i-1];
        result.appendChild(option);   
        instructionSet += i + ": " + inRecipe.ingredients[i-1] + "<br>";
    }
    document.querySelector("#ingredientArray").value = inRecipe.ingredients;
    document.querySelector("#ingredientList").innerHTML=instructionSet; // list the instructions so far
    // console.log(list);
    // console.log(newRecipe.ingredients);
}






function displayRecipe(name,image,category,serves,preparationTime,cookTime,ingString,instString) {
    let ingredients = [];
    let instructions = [];
    let pos = 0;
    let firstIng = "";
    let firstInst = "";

    while (ingString.indexOf("*")!=-1)    {
        pos = ingString.indexOf("*");
        firstIng = ingString.slice(0,pos);
        ingredients.push(firstIng);
        ingString=ingString.slice(pos+1,ingString.length);
    }
    ingredients.push(ingString);

    while (instString.indexOf("*")!=-1)    {
        pos = instString.indexOf("*");
        firstInst = instString.slice(0,pos);
        instructions.push(firstInst);
        instString=instString.slice(pos+1,instString.length);
    }
    instructions.push(instString);


    document.querySelector(".container1").style.opacity=1;
    document.querySelector(".container1").style.height="auto";
    document.querySelector(".container1").style.zIndex=5;
    document.querySelector(".container1").style.transform="translate(1000px,-600px)";
    document.querySelector("#disRecipes").style.display="none";
    i=0;
    let multiplier =1;
    //alert(name);
    document.querySelector("#details h1").innerHTML=name;
    document.querySelector("#details h1+img").src=image;
    document.querySelector("#details h1+img+h3").innerHTML="Servings: " + serves;
    document.querySelector("#details h1+img+h3+h3").innerHTML="Preparation Time: " + preparationTime;
    document.querySelector("#details h1+img+h3+h3+h3").innerHTML="Cook Time: " + cookTime;

    let unit=0;
    let temp ="";
    let ingButton= document.createElement("p");
    ingButton.innerHTML="<button>Ingredient List</button>";// create button to list ingredients
    ingButton.addEventListener('click', function(){
        if (ingButton.innerHTML=="<button>Ingredient List</button>")   { // list ingredients on button click
            if (multiplier == 1) 
                for (i=1;i<=ingredients.length;i++) { 
                    ingButton.innerHTML += "<p>"+i+": "+ingredients[i-1]+"</p>";
                }
            else {      // multiplier in effect
                for (i=1;i<ingredients.length;i++) { console.log(ingredients.length);
                    if (ingredients[i-1].indexOf(".")==-1)  {  // no decimal number to adjust
                        unit = parseInt(ingredients[i-1]);
                        if ((multiplier*unit)%1!=0) // if its not an even multiple limit decimals
                            temp = ingredients[i-1].replace(unit,(multiplier*unit).toFixed(2));
                        else temp = ingredients[i-1].replace(unit,(multiplier*unit)); // else dont include the .00
                        ingButton.innerHTML+= "<p>"+i+": "+temp+"</p>";}
                    else {      // there is a decimal #
                        unit = ingredients[i-1].slice(0,3);
                        temp = eval(unit*multiplier);
                        unit = ingredients[i-1].slice(3,400);
                        ingButton.innerHTML+= "<p>"+i+": "+temp.toFixed(2)+unit+"</p>";
                    }
                }
                ingButton.innerHTML += "<p>"+i+": "+ingredients[i-1]+"</p>";
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
            for (i=1;i<=instructions.length;i++) {        // build instruction set
                instButton.innerHTML += "<p>"+i+": "+instructions[i-1]+"</p>";   // Theoretical instruction set multiplier
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





function displayRecipe1() {
    document.querySelector(".container1").style.opacity=1;
    document.querySelector(".container1").style.height="auto";
    document.querySelector(".container1").style.zIndex=5;
    document.querySelector(".container1").style.transform="translate(1000px,-600px)";
    document.querySelector("#disRecipes").style.display="none";
    displayFullRecipe(recipeDisplayed1);
}

function displayRecipe2() {
    document.querySelector(".container1").style.opacity=1;
    document.querySelector(".container1").style.height="auto";
    document.querySelector(".container1").style.zIndex=5;
    document.querySelector(".container1").style.transform="translate(1000px,-600px)";
    document.querySelector("#disRecipes").style.display="none";
    displayFullRecipe(recipeDisplayed2);
}

function displayRecipe3() {
    document.querySelector(".container1").style.opacity=1;
    document.querySelector(".container1").style.height="auto";
    document.querySelector(".container1").style.zIndex=5;
    document.querySelector(".container1").style.transform="translate(1000px,-600px)";
    document.querySelector("#disRecipes").style.display="none";
    displayFullRecipe(recipeDisplayed3);
}




function getRecipes() {
    let recipeCat = document.querySelector("#viewRecipes").value;
    //alert(recipeCat);
    var xmlhttp = new XMLHttpRequest();
	let x="";
	xmlhttp.onreadystatechange=function(){
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		//x= JSON.parse(xmlhttp.responseText);
		
        newRecipe = xmlhttp.responseText;
        
        document.querySelector("#showRecipes").innerHTML = newRecipe;
		}
	}
	switch (recipeCat)  {
        case 'Entree':xmlhttp.open("GET","DATABASE.php?recipeType=Entree",true);break;
        case 'Drink':xmlhttp.open("GET","DATABASE.php?recipeType=Drink",true);break;
        case 'Appetier':xmlhttp.open("GET","DATABASE.php?recipeType=Appetizer",true);break;
        case 'Dessert':xmlhttp.open("GET","DATABASE.php?recipeType=Dessert",true);break;
        default:xmlhttp.open("GET","DATABASE.php?recipeType=Magic",true);break;
    }	
	//xmlhttp.open("GET","DATABASE.php?recipeType=Magic",true);
		
	xmlhttp.send();
}





function displayFullRecipe(recipeDetails)  { 
    i=0;
    let multiplier =1;
    
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
                for (i=1;i<=recipeDetails.ingredients.length;i++) { 
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
