var strength = 
{
    0: "Muito Fraca",
    1: "Fraca",
    2: "Mediana",
    3: "Boa",
    4: "Ótima"
}

var txtNovaSenha = document.getElementById('txtNovaSenha');
var meter = document.getElementById('password-strength-meter');
var text = document.getElementById('password-strength-text');

txtNovaSenha.addEventListener('input', function (){
    $("#divPasswordStrendthMeter").removeClass("d-none");
    var val = txtNovaSenha.value;
    var result = zxcvbn(val);

    // Update the password strength meter
    meter.value = result.score;

    // Update the text indicator
    if (val !== "") 
    {
        text.innerHTML = strength[result.score];
    }
    else
    {
        text.innerHTML = "Digite sua senha";
    }
});