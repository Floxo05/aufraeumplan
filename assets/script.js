const trElements = document.querySelectorAll('#table tr');

trElements.forEach((tr) => {
    // Überprüfe, ob das <tr>-Element eine ID hat, bevor der Listener hinzugefügt wird
    if (tr.id) {
        tr.addEventListener("click", () => {
            // Überprüfe, ob das Element bereits die Klasse "selected" hat
            const isSelected = tr.classList.contains("selected");

            if (isSelected) {
                // Wenn das Element bereits ausgewählt ist, entferne die Klasse und das Data-Attribut
                tr.classList.remove("selected");
                tr.removeAttribute("data-selected");
            } else {
                // Wenn das Element noch nicht ausgewählt ist, füge die Klasse und das Data-Attribut hinzu
                tr.classList.add("selected");
                tr.setAttribute("data-selected", "true");
            }

            // Hier kannst du den Code schreiben, der beim Klick auf eine Tabellenzeile ausgeführt werden soll
            console.log("Tabellenzeile wurde angeklickt!");
        });
    }
});

document.getElementById("btn_done").addEventListener("click", () => sendDataToServer(true));
document.getElementById("btn_not_done").addEventListener("click", () => sendDataToServer(false));

async function sendDataToServer(isDone) {

    await setIcon(isDone);

    const selectedRows = document.querySelectorAll("#table tr[data-selected='true']");
    const selectedIDs = Array.from(selectedRows).map((row) => row.id);
    const currentDate = document.getElementById("currentDate").value;

    // Hier fügen wir den Boolean-Wert (isDone) dem zu sendenden Datenobjekt hinzu
    const requestData = {
        selectedIDs: selectedIDs,
        isDone: isDone,
        date: currentDate
    };

    // AJAX-POST-Request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/public/request.php", true);
    xhr.setRequestHeader("Content-type", "application/json"); // Verwende jetzt JSON als Datenformat

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            console.log(xhr.responseText);
        }
    };

    xhr.send(JSON.stringify(requestData)); // Daten als JSON verschicken
}

function getCurrentDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, "0");
    const day = String(today.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
}

document.getElementById("currentDate").value = getCurrentDate();


async function setIcon(isDone) {
    if (isDone) {
        document.getElementById('icon').setAttribute('src', './assets/icons/tick.svg');
    } else {
        document.getElementById('icon').setAttribute('src', './assets/icons/cross.svg');
    }
}
