function start() {
  alert("Welcome! Let's get started.");
}

function join() {
  alert("Thank you for joining!");
}


let cart = [];
let total = 0;

function toggleDarkMode(){
  document.body.classList.toggle("dark");
}

/* ADD TO CART */
function addToCart(name, price){
  cart.push({name, price});
  total += price;
  updateCart();
}

/* UPDATE CART */
function updateCart(){
  const cartItems = document.getElementById("cartItems");
  const count = document.getElementById("cartCount");
  const totalDisplay = document.getElementById("totalPrice");

  cartItems.innerHTML = "";

  cart.forEach((item,index)=>{
    cartItems.innerHTML += `
      <div class="cart-item">
        ${item.name} - $${item.price}
        <button onclick="removeItem(${index})">X</button>
      </div>
    `;
  });

  count.innerText = cart.length;
  totalDisplay.innerText = total;
}

/* REMOVE */
function removeItem(index){
  total -= cart[index].price;
  cart.splice(index,1);
  updateCart();
}

/* TOGGLE CART */
function toggleCart(){
  document.getElementById("cartPanel").classList.toggle("active");
}

/* CHECKOUT */
function checkout(){
  if(cart.length === 0){
    alert("Cart is empty");
  }else{
    alert("Order Successful! Total: $" + total);
    cart = [];
    total = 0;
    updateCart();
  }
}

/* CONTACT VALIDATION */
function validateForm(){
  let name = document.getElementById("name").value;
  let email = document.getElementById("email").value;

  if(name === "" || email === ""){
    alert("Please fill all fields");
    return false;
  }

  alert("Message Sent Successfully!");
  return true;
}