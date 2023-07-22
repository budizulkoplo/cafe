// Mencari semua elemen form dan input di setiap halaman
var forms = document.querySelectorAll("form");
var inputs = document.querySelectorAll("input");

// Menambahkan atribut autocomplete="off" ke setiap elemen form dan input
for (var i = 0; i < forms.length; i++) {
  forms[i].setAttribute("autocomplete", "off");
}

for (var i = 0; i < inputs.length; i++) {
  inputs[i].setAttribute("autocomplete", "off");
}
