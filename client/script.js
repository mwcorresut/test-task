function addRows() {
    fetch("../server/index.php")
        .then((response) => response.json())
        .then((data) => {
            const table = document.querySelector(".table-body");

            data.forEach((item) => {
                const row = document.createElement("tr");
                row.classList.add("table-cell");
                row.innerHTML = `
                    <td>${item.id}</td>
                    <td>${item.place}</td>
                    <td>${item.name}</td>
                    <td>${item.city}</td>
                    <td>${item.car}</td>
                    <td>${item.attempts[0] || 0}</td>
                    <td>${item.attempts[1] || 0}</td>
                    <td>${item.attempts[2] || 0}</td>
                    <td>${item.attempts[3] || 0}</td>
                    <td>${item.sum || 0}</td>
                `;
                table.appendChild(row);
            });
        })
        .catch((error) => console.error("Ошибка", error));
}

function sortTable(e, ord) {
    const table = document.querySelector("table");
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));

    const colNum = e.target.cellIndex + 1;
    console.log(colNum);
    rows.sort((a, b) => {
        const cellA = a.querySelector(`td:nth-child(${colNum})`);
        const cellB = b.querySelector(`td:nth-child(${colNum})`);

        if (cellA && cellB) {
            const valueA = parseInt(cellA.textContent);
            const valueB = parseInt(cellB.textContent);

            return ord === "asc" ? valueA - valueB : valueB - valueA;
        } else {
            return 0;
        }
    });

    rows.forEach((row) => tbody.appendChild(row));
}

const sortableCols = document.querySelectorAll(".sortable");
// TODO посмотреть на предмет какого-то id колонки в массиве,
// чтобы не привязываться к attemptCol

for (let i = 0; i < sortableCols.length; i++) {
    sortableCols[i].addEventListener("click", (e) => {
        if (e.target.className.includes("desc")) {
            sortTable(e, "asc");
            e.target.classList.remove("desc");
            e.target.classList.add("asc");
        } else {
            sortTable(e, "desc");
            e.target.classList.remove("asc");
            e.target.classList.add("desc");
        }
    });
}

addRows();
