var h2 = document.getElementsByTagName("h2");

var inputList = Array.prototype.slice.call(h2);

var response = [];

inputList.forEach((alumnoData) => {
    var r = {}
    var alumno_array = alumnoData.textContent.split("DNI");
    r["numero_documento"] = alumno_array[1].trim();
    var nombre_completo = alumno_array[0].split("  ");
    r["nombres"] = nombre_completo[1].trim();
    r["apellidos"] = nombre_completo[0].replace(/[0-9]/g, '').trim();
    response.push(r);
});
console.log(JSON.stringify(response));