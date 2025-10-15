function Quad () {
    let output = document.getElementById('output')
	var a = document.getElementById("a").value;
	var b = document.getElementById("b").value;
	var c = document.getElementById("c").value;
	var e = (Math.sqrt(b * b - 4 * a * c));
    var f = (-b + e) / (2 * a);
    var g = (-b - e) / (2 * a);
    if (isNaN(f)) { 
        output.innerHTML = "Invalid input, please valid numbers and equations only";
    }else if(g == f) {
	    output.innerHTML = "There is only one possible value of x. The only value is: "+g;
    }else{
	    output.innerHTML = "The First value of x is : " + g + " and the second value is: " + f +". Thank you.";
	}
}

function Plain() {
    let output = document.getElementById('output');
    let a = parseFloat(document.getElementById("a").value);
    let b = parseFloat(document.getElementById("b").value);
    let c = parseFloat(document.getElementById("c").value);

    let outputText;

    if (isNaN(a) || isNaN(b) || isNaN(c)) {
        outputText = "Invalid or empty equation!";
    } else {
        let p = -c / a;
        let ha = b / (2 * a);
        let haa = Math.pow(ha, 2);
        let hab = p + haa;
        let habb = Math.sqrt(hab);
        let e = Math.sqrt(b * b - 4 * a * c);
        let h = (-b + e) / (2 * a);
        let g = (-b - e) / (2 * a);

        let completingSquareText = `
            Solution to the Quadratic Equation.<br>
            So, the equation is: ${a}x² + ${b}x + ${c} = 0.<br>
            WE ARE USING COMPLETING THE SQUARE METHOD.<br><br>
            <h3>STEP 1</h3>
            To begin solving, divide everything by the coefficient of x².<br>
            In this case, the coefficient is: ${a}.<br>
            The equation becomes: x² + ${b / a}x + ${c / a} = 0.<br>
            After dividing, move ${c / a} to the right-hand side of the equation.<br>
            The equation would look like this: x² + ${b / a}x = ${p}.<br><br>
            <h3>STEP 2</h3>
            Now add the square of half the coefficient of x to both sides of the equation.<br>
            The coefficient of x is: ${b / a}.<br>
            Solving further makes the equation: x² + ${b / a}x + (${b / (2 * a)})² = ${p} + (${b / (2 * a)})² = ${p + haa}.<br><br>
            <h3>STEP 3</h3>
            Solving further makes the equation become: x² + ${b / a}x + (${b / (2 * a)})² = ${hab}.<br>
            Pick the squares on the left-hand side of the equation, then group them in a bracket.<br>
            The equation becomes: (x + ${b / (2 * a)})² = ${hab}.<br><br>
            <h3>STEP 4</h3>
            Now find the square root (√) of both sides.<br>
            Our equation is now like this: x + ${b / (2 * a)} = ±${habb}.<br><br>
            <h3>STEP 5</h3>
            Make x the subject of the equation/formula by collecting like terms.<br>
            The equation becomes: x = ${-ha} ± ${habb}.<br><br>
            Solving further makes x to equal: ${g} OR ${h}.
        `;

        if (c < 0) {
            outputText = completingSquareText;
        } else {
            outputText = completingSquareText.replace("Make x the subject", "Make x subject");
        }
    }

    output.innerHTML = outputText;
}

function Equation3() { 
    let output = document.getElementById('output')
	var A = document.getElementById("la").value;
    var B = document.getElementById("lb").value;
    var C = document.getElementById("lc").value;
    var D = document.getElementById("ld").value;

    var E = document.getElementById("le").value;
    var F = document.getElementById("lf").value;
    var G = document.getElementById("lg").value;
    var H = document.getElementById("lh").value;
    var I = document.getElementById("li").value;
    var J = document.getElementById("lj").value;
    var K = document.getElementById("lk").value;
    var L = document.getElementById("ll").value;
        
        King = A*(F*K-J*G)-B*(E*K-G*I)+C*(E*J-F*I);
        Y = (A*(H*K-G*L)-D*(E*K-G*I)+C*(E*L-H*I))/King;
        X = (D*(F*K-G*J)- B*(H*K-G*L)+C*(H*J-F*L))/King;
        Z = (A*(F*L-H*J)-B*(E*L-H*I)+D*(E*J-F*I))/King;
        if (isNaN(X)){
            output.innerHTML = "Invalid or empty Equation!";
        }else if (isNaN(Y)) {
                output.innerHTML = "Invalid or empty Equation!";
            }else if  (isNaN(Z)) {
                output.innerHTML = "Invalid or empty Equation!";
            }else{
        output.innerHTML = "The solution to the equation: X is = " + X +", Y is = " + Y + " and Z is = " + Z; } 
    
    
}

function PlainEqn3 () 
{

        let output = document.getElementById('output')
        var A = document.getElementById("la").value;
        var B = document.getElementById("lb").value;
        var C = document.getElementById("lc").value;
        var D = document.getElementById("ld").value;
    
        var E = document.getElementById("le").value;
        var F = document.getElementById("lf").value;
        var G = document.getElementById("lg").value;
        var H = document.getElementById("lh").value;
        var I = document.getElementById("li").value;
        var J = document.getElementById("lj").value;
        var K = document.getElementById("lk").value;
        var L = document.getElementById("ll").value;

        
        King = A*(F*K-J*G)-B*(E*K-G*I)+C*(E*J-F*I);
        Y = (A*(H*K-G*L)-D*(E*K-G*I)+C*(E*L-H*I))/King;
        X = (D*(F*K-G*J)- B*(H*K-G*L)+C*(H*J-F*L))/King;
        Z = (A*(F*L-H*J)-B*(E*L-H*I)+D*(E*J-F*I))/King;
    
        if (isNaN(X)){
            output.innerHTML = "Invalid or empty Equation!";
        }else if (isNaN(Y)) {
                output.innerHTML = "Invalid or empty Equation!";
            }else if  (isNaN(Z)) {
                output.innerHTML = "Invalid or empty Equation!";
            }else{
        output.innerHTML = "The solution to the equation: X is = " + X +", Y is = " + Y + " and Z is = " + Z; }
}


function PlainEqn2 () 

{

    let output = document.getElementById('output')
    let br= document.createElement("br")
    output.appendChild(br)

    let h3= document.createElement("h3")
    output.appendChild(h3)

    let b= document.createElement("b")
    output.appendChild(b)


    let div= document.createElement("div")
    output.appendChild(div)

    let span= document.createElement("span")
    output.appendChild(span)

    let sub= document.createElement("sub")
    output.appendChild(sub)

    var A = document.getElementById("a").value;
    var B = document.getElementById("b").value;
    var C = document.getElementById("c").value;
    var D = document.getElementById("d").value;

    var E = document.getElementById("e").value;
    var F = document.getElementById("f").value;

var hd1 = "<br>" + "<h3>" + "2 Unknowns Calculator"  + "<br>" + "</h3>";
var p= "The Equations provided are: " + "<br>"+ A +"X + "+ B +"Y = "+ C+"<br>"+ D +"X + "+ E +"Y = "+ F + "<br>";

let hd2 = "<br>" + "<b>" + "STEP 1" + "</b>" + "<br>"
var p1 = "We will use Crammers Rule or Method to solve this equation. This method is suitable for Systems of Linear equations with more than one unknown variable."+"<br>"+"So we continue by converting the given equation into a matrix <i>P</i>" + "<br>";

let hd3 = "<b>" + "STEP 2" + "</b>" + "</br>";

var p2 = "<div>" + "We proceed further by creating matrix P<sub>1</sub>, P<sub>2</sub>, and P<sub>3</sub>. The values of each would be derived thus: " + "<br>" + "<div style='display: flex; align-items: center; justify-content: center;'>P" + "<sub>" + 1 +"</sub>" + "=" + "<div style='display: flex; align-items:center; margin-left:20px;'>" +
"<div style= 'display:flex;justify-contents:space-between;gap:2rem align-items:center;row-gap:2rem; border-left: 2px solid #000; border-right: 2px solid #000;padding: 0 10px; margin-right: 10px;'>" + "<div style='display:flex; flex-direction: column;margin-right:30px;'>" + "<span>" + C + "</span>" + "<span>" + F + "</span>" +  "</div>" + "<div style='display:flex; flex-direction: column;'>" + "<span>" + B + "</span>" + "<span>" + E + "</span>" + "</div>" +
"</div>" + "<br>" + "<br>" + "</div></div>"
+ "<br><br><div style='display: flex; align-items: center; justify-content: center;'>P" + "<sub>" + 2 +"</sub>" + "=" + "<div style='display: flex; align-items:center; margin-left:20px;'>" +
"<div style= 'display:flex;justify-contents:space-between;gap:2rem align-items:center;row-gap:2rem; border-left: 2px solid #000; border-right: 2px solid #000;padding: 0 10px; margin-right: 10px;'>" + "<div style='display:flex; flex-direction: column;margin-right:30px;'>" + "<span>" + C + "</span>" + "<span>" + F + "</span>" +  "</div>" + "<div style='display:flex; flex-direction: column;'>" + "<span>" + B + "</span>" + "<span>" + E + "</span>" + "</div>" +
"</div>" + "<br>" + "<br>" + "</div></div>"
+ "<br><br><div style='display: flex; align-items: center; justify-content: center;'>P" + "<sub>" + 3 +"</sub>" + "=" + "<div style='display: flex; align-items:center; margin-left:20px;'>" +
"<div style= 'display:flex;justify-contents:space-between;gap:2rem align-items:center;row-gap:2rem; border-left: 2px solid #000; border-right: 2px solid #000;padding: 0 10px; margin-right: 10px;'>" + "<div style='display:flex; flex-direction: column;margin-right:30px;'>" + "<span>" + C + "</span>" + "<span>" + F + "</span>" +  "</div>" + "<div style='display:flex; flex-direction: column;'>" + "<span>" + B + "</span>" + "<span>" + E + "</span>" + "</div>" +
"</div>" + "<br>" + "<br>" + "</div></div>"

let hd4 = "<b>" + "STEP 3" + "</b>" + "<br>";
var p3 = "Find the determinant of each." + "<br>" + "Finding the determinant would be done thus:" + "<br" + " The Determinants are D1, D2, D3" + "<br>" + "where D1= det P1, D2=det P2, D3= det P3 " + "<br>";

let hd5 = "<b>" + "STEP 4" + "</b>" + "<br>";
var p4 = "With the above, we can find X and Y. \n X= D1/D3, Y= D2/D3" + "<br>";
output.innerHTML = hd1 + p + hd2 + p1 + hd3 + p2 + hd4 + p3 + hd5 + p4;
    
    var X, Y;

    X= (C*E - B*F)/(A*E - B*D);

    Y= (A*F - C*D)/(A*E - B*D);

    if (isNaN(X)){
        output.innerHTML = "Invalid or empty Equation!";
    }else if (isNaN(Y)) {
            output.innerHTML = "Invalid or empty Equation!";
        }else{
    output.innerHTML = hd1 + p + hd2 + p1 + hd3 + p2 + hd4 + p3 + hd5 + p4;
        

        }
    }


    function SolveEqn2 () 

{

    let output = document.getElementById('output')
    var A = document.getElementById("a").value;
    var B = document.getElementById("b").value;
    var C = document.getElementById("c").value;
    var D = document.getElementById("d").value;

    var E = document.getElementById("e").value;
    var F = document.getElementById("f").value;

    var X, Y;

    X= (C*E - B*F)/(A*E - B*D);

    Y= (A*F - C*D)/(A*E - B*D);

    if (isNaN(X)){
        output.innerHTML = "Invalid or empty Equation!";
    }else if (isNaN(Y)) {
            output.innerHTML = "Invalid or empty Equation!";
        }else{
    output.innerHTML = "The solution to the equation: X is = " + X +", Y is = " + Y; 
        

        }
    }

    

function Even() {
    let output = document.getElementById('output')
	var number = document.getElementById("tt").value;
    if (number == 0 || isNaN(number)) {
        output.innerHTML = "Invalid input!";
    }else if (number%2 == 0) {
        output.innerHTML = number +" is an even number.";
    }else{
        output.innerHTML = number +" is an odd number.";
    }
}

function gen(){
	var re = document.getElementById("rN").value;	
	var a = document.getElementById("sN").value;	
	var ad1 = document.getElementById("sA").value;	
	var letter, conclusion;
	var salute = "Yours Faithfully,";
			
	if (ad1 == "") {
	    alert("Empty value! Please write an address!");
	}else{
	    var sp = ad1.split("\n");
        let count = sp.length;
        let output = document.querySelector("#output__letter")
        document.getElementById("output__letter").style.fontFamily = "handFont";
        for (let i = 0; i < count; i++) {
            para = document.createElement("p")
            output.appendChild(para)
            para.style.marginLeft = "60%"
            para.innerHTML = sp[i]
        }

        br = document.createElement("br")
        output.appendChild(br)

        dear = document.createElement("p")
        output.appendChild(dear)
        dear.innerHTML = "Dear " + re + (",");

        letter1="How you all doing at home? Message from your favorite: " + a + ". Sorry for the delay in writing to you. I have been really busy. I hope my letter finds you in the best of health."
        
        letter2= "It has been almost a month since I transferred to the boarding school. I am very happy in my new school. The classes and dormitories are very spacious. There are well equipped science laboratories and a library with a great collection of books."; 

        letter3= "The school has amazing sports facilities such as basketball court, volleyball court, lawn tennis court and even a swimming pool. I believe my life here will be interesting and enjoyable. Although I have made many new friends, I still miss you and the others."
        
        conclusion= "Anyway, let me stop here.  I really look forward to hearing from you. Please extend my regards to those at home.";

        lt = document.createElement("p")
        output.appendChild(lt)
        lt.innerHTML = letter1+letter2+letter3;

        con = document.createElement("p")
        output.appendChild(con)
        con.innerHTML = conclusion;

        br = document.createElement("br")
        output.appendChild(br)

        salut = document.createElement("p")
        output.appendChild(salut)
        salut.style.marginLeft = "60%"
        salut.innerHTML = salute;

        sign = document.createElement("p")
        output.appendChild(sign)
        sign.style.marginLeft = "60%"
        sign.innerHTML = a;
		
		alert("Congrats! Your letter was Generated, kindly click ok to continue!");
	}
}

function Clear1() {
    let output = document.getElementById('output')
    document.getElementById("a").value = "";
    document.getElementById("b").value = "";
    document.getElementById("c").value = "";
    output.innerHTML = "";
}

function Clear2() {
    let output = document.getElementById('output')
    document.getElementById("la").value = "";
    document.getElementById("lb").value = "";
    document.getElementById("lc").value = "";
    document.getElementById("ld").value = "";

    document.getElementById("le").value = "";
    document.getElementById("lf").value = "";
    document.getElementById("lg").value = "";
    document.getElementById("lh").value = "";
    document.getElementById("li").value = "";
    document.getElementById("lj").value = "";
    document.getElementById("lk").value = "";
    document.getElementById("ll").value = "";
    output.innerHTML = "";
}



function Clear2Unknowns() {
    let output = document.getElementById('output')
    document.getElementById("a").value = "";
    document.getElementById("b").value = "";
    document.getElementById("c").value = "";
    document.getElementById("d").value = "";

    document.getElementById("e").value = "";
    document.getElementById("f").value = "";
    
    output.innerHTML = "";
}

function Clear3(){ 
    let output = document.getElementById('output')
    document.getElementById("tt").value = "";
    output.innerHTML = "";
}

