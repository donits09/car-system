  
// EXPORT TO CSV
function convertToCSV(table) {
    let rows = table.querySelectorAll('tr');
    let csv = [];

    let today = new Date();
    let dateStr = `${today.getFullYear()}-${(today.getMonth() + 1).toString().padStart(2, '0')}-${today.getDate().toString().padStart(2, '0')}`;
    let title = `CAR LIST AS OF ${dateStr}`;
    csv.push(title); 

    csv.push(''); 

    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll('td, th');

        for (let j = 0; j < cols.length - 1; j++) {
            let data = cols[j].innerText.replace(/"/g, '""');
            row.push('"' + data + '"');
        }

        csv.push(row.join(','));
    }

    return csv.join('\n');
}

function downloadCSV(csv, filename) {
    let csvFile;
    let downloadLink;

    csvFile = new Blob([csv], {type: 'text/csv'});
    downloadLink = document.createElement('a');

    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);

    downloadLink.click();
}

document.getElementById('export_csv').addEventListener('click', function() {
    let table = document.getElementById('car-list-table');
    let csv = convertToCSV(table);
    let today = new Date();

    let filename = `car_list_asof_${today.getFullYear()}-${(today.getMonth() + 1).toString().padStart(2, '0')}-${today.getDate().toString().padStart(2, '0')}.csv`;
    console.log('CSV Filename:', filename);

    downloadCSV(csv, filename);
});


// EXPORT TO PDF
/* function exportPDF() {
    const element = document.getElementById('data-table');
    
    const clonedElement = element.cloneNode(true);
    clonedElement.setAttribute('style', 'font-size: 8px; font-family: Arial; background-color: white;'); 

    const headerCells = clonedElement.querySelectorAll('th:last-child');
    headerCells.forEach(cell => cell.parentNode.removeChild(cell));

    const rows = clonedElement.getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        if (cells.length > 0) {
            rows[i].removeChild(cells[cells.length - 1]);
        }
    }

    const options = {
        margin: 10,
        filename: 'car_list.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().from(clonedElement).set(options).save();
}

document.getElementById('export_pdf').addEventListener('click', function() {
    exportPDF();
}); */


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
