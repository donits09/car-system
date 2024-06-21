  
// EXPORT TO CSV
function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    csvFile = new Blob([csv], {type: 'text/csv'});

    downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";

    document.body.appendChild(downloadLink);
    downloadLink.click();
}

function exportTableToCSV(filename) {
    var csv = [];
 
    var date = new Date();
    var day = ('0' + date.getDate()).slice(-2);
    var month = ('0' + (date.getMonth() + 1)).slice(-2);
    var year = date.getFullYear();
   
    var currentDate = year + '-' + month + '-' + day;

    csv.push(`"CAR LIST AS OF ${currentDate}"`);
    csv.push("");

    var rows = document.querySelectorAll("#car-list-table tr");

    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");

        for (var j = 0; j < cols.length - 1; j++) {
            row.push('"' + cols[j].innerText + '"');
        }
        csv.push(row.join(","));
    }

    var finalFilename = filename + year + month + day + ".csv";

    downloadCSV(csv.join("\n"), finalFilename);
}

document.getElementById("export_csv").addEventListener("click", function () {
    exportTableToCSV("car_list_asof_");
});

// EXPORT TO PDF
function exportPDF() {
    let account_no = document.getElementById('buyer_acc_no').value;

    if (account_no) {
        let url = `../../print/pdf_buyer.php?id=${encodeURIComponent(account_no)}`;
        window.open(url, '_blank');
    } else {
        console.error('Account number is empty or not found.');
    }
}

document.getElementById('export_pdf').addEventListener('click', exportPDF);
