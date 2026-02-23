function createPerfume(){

    const name = document.getElementById("perfumeName").value;
    const top = document.getElementById("topNote").value;
    const heart = document.getElementById("heartNote").value;
    const base = document.getElementById("baseNote").value;
    const intensity = document.getElementById("intensity").value;

    if(name === ""){
        alert("Please enter a perfume name.");
        return;
    }

    const result = `
        <strong>Name:</strong> ${name} <br>
        <strong>Top Note:</strong> ${top} <br>
        <strong>Heart Note:</strong> ${heart} <br>
        <strong>Base Note:</strong> ${base} <br>
        <strong>Intensity Level:</strong> ${intensity}/5
    `;

    document.getElementById("resultBox").innerHTML = result;
    document.getElementById("resultSection").style.display = "block";
}

document.addEventListener("DOMContentLoaded", function(){

    const intensitySlider = document.getElementById("intensity");
    const intensityValue = document.getElementById("intensityValue");

    // Set initial value
    intensityValue.textContent = intensitySlider.value;

    // Update when slider moves
    intensitySlider.addEventListener("input", function(){
        intensityValue.textContent = this.value;
    });

});


function orderPerfume(){
    alert("Thank you for ordering your custom perfume! We will contact you shortly.");
}