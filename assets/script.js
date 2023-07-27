
const addEventlistener = () => {
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
                // console.log("Tabellenzeile wurde angeklickt!");
            });
        }
    });
}

addEventlistener();


document.getElementById("btn_done").addEventListener("click", () => sendDataToServer(true));
document.getElementById("btn_not_done").addEventListener("click", () => sendDataToServer(false));

async function sendDataToServer(isDone) {

    const selectedRows = document.querySelectorAll("#table tr[data-selected='true']");
    const selectedIDs = Array.from(selectedRows).map((row) => row.id);
    const currentDate = document.getElementById("currentDate").value;

    await setIcon(isDone, selectedIDs);

    // Hier fügen wir den Boolean-Wert (isDone) dem zu sendenden Datenobjekt hinzu
    const requestData = {
        selectedIDs: selectedIDs,
        isDone: isDone,
        date: currentDate
    };

    // AJAX-POST-Request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/public/saveAktivitaet.php", true);
    xhr.setRequestHeader("Content-type", "application/json"); // Verwende jetzt JSON als Datenformat

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // console.log(xhr.responseText);
        }
    };

    xhr.send(JSON.stringify(requestData)); // Daten als JSON verschicken
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
}

function formatDateReadable(inputString) {
    // Step 1: Parse the input string into a Date object
    const date = new Date(inputString);

    // Step 2: Extract day, month, and year components from the Date object
    const day = date.getDate();
    const month = date.getMonth() + 1; // Months are zero-based, so we add 1
    const year = date.getFullYear();

    // Step 3: Create a new formatted string in "d.m.Y" format
    return `${day < 10 ? '0' : ''}${day}.${month < 10 ? '0' : ''}${month}.${year}`;
}


async function setIcon(isDone, selectedIDs) {

    selectedIDs.forEach((ids) => {
        if (isDone) {
            document.getElementById(`icon${ids}`).setAttribute('src', './assets/icons/tick.svg');
        } else {
            document.getElementById(`icon${ids}`).setAttribute('src', './assets/icons/cross.svg');
        }
    })
}

document.getElementById('left-arrow').addEventListener('click', () => {
    changeTime(-1);
})

document.getElementById('right-arrow').addEventListener('click', () => {
    changeTime(1);
})


const setTime = (time) => {

    // console.log(time)

    document.getElementById('time-span').textContent = formatDateReadable(time);
    document.getElementById('currentDate').value = time;

}

setTime(formatDate(new Date()));

const changeTime = (offset) => {

    const currentDate = new Date(document.getElementById('currentDate').value);
    currentDate.setTime(currentDate.getTime() + offset * 86400000);

    // Das Datum im Format "Y-m-d" zurückgeben
    let date = formatDate(currentDate);

    const requestData = {
        newTime: date
    };

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/public/loadDateData.php", true);
    xhr.setRequestHeader("Content-type", "application/json");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // console.log(xhr.responseText);
            const responseObj = JSON.parse(xhr.responseText);
            updateTable(responseObj.data);
        }
    };

    xhr.send(JSON.stringify(requestData));

    setTime(date);
    // console.log(date)
}


function updateTable(data) {
    // console.log(data)
    const tableBody = document.getElementById('data-table');

    // Vorhandene Zeilen löschen
    while (tableBody.firstChild) {
        tableBody.removeChild(tableBody.firstChild);
    }

    // Neue Zeilen aus den Daten erstellen und einfügen
    data.forEach((entry) => {
        const row = document.createElement('tr');
        row.id = entry.aktivitaeten_id;

        // Erstelle Zellen und füge die Daten hinzu
        const nameCell = document.createElement('td');
        nameCell.textContent = entry.name;
        row.appendChild(nameCell);

        const raumCell = document.createElement('td');
        raumCell.textContent = entry.raum;
        row.appendChild(raumCell);

        const aktivitaetCell = document.createElement('td');
        aktivitaetCell.textContent = entry.aktivitaet;
        row.appendChild(aktivitaetCell);

        const iconCell = document.createElement('td');
        const iconImg = document.createElement('img');
        iconImg.src = `./assets/icons/${entry.icon}.svg`;
        iconImg.id = `icon${entry.aktivitaeten_id}`;
        iconCell.appendChild(iconImg);
        row.appendChild(iconCell);

        // Füge die Zeile zum tbody hinzu
        tableBody.appendChild(row);
    });

    addEventlistener();
}
