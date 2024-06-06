$(document).ready( function () {
  $('#data-table').DataTable();
} );

(function() {
    let zoomLevel = 1;
    const tableContainer = document.querySelector('.table-container');
    const table = tableContainer.querySelector('table');
    
    function adjustZoom(delta) {
      zoomLevel += delta;
      table.style.transform = `scale(${zoomLevel})`;
      tableContainer.scrollLeft += 100 * delta; 
    }
  
  })();
  
