document.getElementById('filter').addEventListener('click', function() {
    let startDate = new Date(document.getElementById('start_date').value);
    let endDate = new Date(document.getElementById('end_date').value);
    let rows = document.querySelectorAll('#car-type-body tr');
    
    rows.forEach(row => {
        let payDate = new Date(row.querySelector('.tran-date').textContent);
        if ((isNaN(startDate) || payDate >= startDate) && (isNaN(endDate) || payDate <= endDate)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.getElementById('reset').addEventListener('click', function() {
    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value = '';
    let rows = document.querySelectorAll('#car-type-body tr');
    rows.forEach(row => {
        row.style.display = '';
    });
});

function convertToCSV(table) {
    let rows = table.querySelectorAll('tr');
    let csv = [];

    let mainHeader = document.querySelector('.main_header');
    let companyName = mainHeader.querySelector('#header').textContent.trim();
    let reportTitle = mainHeader.querySelector('#subheader').textContent.trim();
    let currentDate = mainHeader.querySelector('#current_date').textContent.trim();

    csv.push(`"${companyName}"`);
    csv.push(`"${reportTitle}"`);
    csv.push(`"${currentDate}"`);
    csv.push('');

    csv.push('"No","CAR No.","Payment Type","Account No.","Name","Location","Cash","Check","Transaction Date","Encoder"');

    rows.forEach(row => {
        let rowData = [];
        let cols = row.querySelectorAll('td');

        for (let j = 0; j < cols.length; j++) {
            let data = cols[j].innerText.replace(/"/g, '""');

            if (j === 7) {
                let paymentType = data.trim();
                let amount = cols[6].innerText.trim().replace(/"/g, '""');

                if (paymentType === "Cash") {
                    rowData.push(`"${amount}"`);
                    rowData.push('""');
                } else if (paymentType === "Check") {
                    rowData.push('""');
                    rowData.push(`"${amount}"`);
                } else {
                    rowData.push('""'); 
                    rowData.push('""'); 
                }
            } else if (j !== 6) {
                rowData.push(`"${data}"`);
            }
        }

        csv.push(rowData.join(','));
    });

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
    let table = document.getElementById('data-table');
    let csv = convertToCSV(table);
    let today = new Date();

    let filename = `car_list_asof_${today.getFullYear()}-${(today.getMonth() + 1).toString().padStart(2, '0')}-${today.getDate().toString().padStart(2, '0')}.csv`;
    console.log('CSV Filename:', filename);

    downloadCSV(csv, filename);
});

