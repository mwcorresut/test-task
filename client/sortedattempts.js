const btn = document.querySelector("#sort-button");

btn.addEventListener("click", () => {
    sortTable(6);
});

function sortTable(item) {
    const table = document.querySelector(".table-body");
    const rows = Array.from(table.querySelector("tr"));

    rows.sort((a, b) => {
        const cellA = a.querySelector(`td:nth-child(${item})`);
        const cellB = b.querySelector(`td:nth-child(${item})`);
        console.log(item);
        const valueA = parseInt(cellA.textContent);
        const valueB = parseInt(cellB.textContent);
        return valueB - valueA;
    });

    rows.forEach((row) => table.appendChild(row));
}
