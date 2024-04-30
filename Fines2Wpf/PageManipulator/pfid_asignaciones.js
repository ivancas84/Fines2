function fetchData(numeroComision) {

    fetch('https://programafines.ar/inicial/index4.php?a=12&nom_comision=' + numeroComision + '&mi_periodo=2')
        .then(response => response.text())
        .then(data => {
            var h2 = data.split("<h2 align='left'>");

            for (let i = 0; i < h2.length; i++) {
                var firstStr = "index4.php?a=12&b=1&id_alum_per=";
                var secondStr = "&dni_alum_borrar=";
                var thirdStr = "\">Borrar de esta lista";

                // Find first position
                const firstPos = h2[i].indexOf(firstStr);
                if (firstPos === -1) {
                    continue; // Start string not found
                }

                // Find second position
                const secondPos = h2[i].indexOf(secondStr, firstPos + firstStr.length);
                if (secondPos === -1) {
                    continue;  // End string not found
                }

                // Find third position
                const thirdPos = h2[i].indexOf(thirdStr, secondPos + secondStr.length);
                if (thirdPos === -1) {
                    continue;  // End string not found
                }

                const pfid = h2[i].substring(firstPos + firstStr.length, secondPos);
                const dni = h2[i].substring(secondPos + secondStr.length, thirdPos);

                console.log(pfid);
                console.log(dni);
            }




        })
        .catch(error => {
            console.error('Error fetching content:', error);

        });
}

  
// List of numeroComision
const numeros = [
    '10068',
    '10171',
];

numeros.forEach(numero => {
    fetchData(numero);
});