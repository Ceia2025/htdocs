const cbxRegion = document.getElementById('region')
cbxRegion.addEventListener('change', getComunas)

const cbxComuna = document.getElementById('comuna')
cbxComuna.addEventListener('change', getEstablecimientos)

const cbxEstablecimiento = document.getElementById('establecimiento')


function fetchAndSetData(url, formdata, targetElement) {
    return fetch(url, {
        method: 'POST',
        body: formdata,
        mode: 'cors'
    })
        .then(response => response.json())
        .then (data => {
                targetElement.innerHTML = data;
        })
        .catch(err => console.log(err));
}

function getComunas(){
    let region = cbxRegion.value;
    let url = 'includes/getcomunas.php';
    let formData = new FormData();
    formData.append('id_region', region);

    fetchAndSetData(url, formData, cbxComuna)
        .then(() => {
            cbxEstablecimiento.innerHTML = ''
            let defaulOption = document.createElement('option');
            defaulOption.value = 0;
            defaulOption.innerHTML = "Seleccionar";
            cbxEstablecimiento.appendChild(defaulOption);
            })
            .catch(err => console.log(err));
}

function getEstablecimientos() {
    let comuna = cbxComuna.value;
    let url = 'includes/getestablecimientos.php';
    let formData = new FormData();
    formData.append("id_comuna", comuna);

    fetchAndSetData(url, formData, cbxEstablecimiento)
        .catch(err => console.log(err));   
}