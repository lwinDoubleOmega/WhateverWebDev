function addToCart(product){
    alert(product + " added to cart!");
}

function subscribe(){
    let email = document.getElementById("email").value;
    if(email === ""){
        alert("Please enter your email.");
    }else{
        alert("Subscribed successfully!");
        document.getElementById("email").value = "";
    }
}
