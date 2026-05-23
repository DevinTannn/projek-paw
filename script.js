// =========================
// FILE : script.js
// =========================

function scrollMenu() {
    document.getElementById("menu").scrollIntoView({
        behavior: "smooth"
    });
}

document.getElementById("formPesan")
    .addEventListener("submit", function (event) {

        event.preventDefault();

        let nama = document.getElementById("nama").value;
        let menu = document.getElementById("menuMakanan").value;
        let jumlah = document.getElementById("jumlah").value;

        document.getElementById("hasil").innerHTML =
            "Pesanan atas nama <b>" + nama + "</b> untuk menu <b>" +
            menu + "</b> sebanyak <b>" + jumlah +
            "</b> berhasil dipesan.";
    });