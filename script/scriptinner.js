function openPopup() {
    document.getElementById('open-modal1').style.display = 'block';
    document.getElementById('open-modal2').style.display = 'block';
    document.getElementById('open-modal3').style.display = 'block';
    document.getElementById('open-modal4').style.display = 'block';
    document.getElementById('open-modal6').style.display = 'block';
    document.getElementById('open-modal7').style.display = 'block';
    document.getElementById('open-modal8').style.display = 'block';
    document.getElementById('open-modal9').style.display = 'block';
    document.getElementById('open-modal10').style.display = 'block';
  }
  
function closePopup(event) {
    event.preventDefault(); // ป้องกันการเปลี่ยนที่ของ URL จากการคลิกลิงก์
    document.getElementById('open-modal1').style.display = 'none';
    document.getElementById('open-modal2').style.display = 'none';
    document.getElementById('open-modal3').style.display = 'none';
    document.getElementById('open-modal4').style.display = 'none';
    document.getElementById('open-modal6').style.display = 'none';
    document.getElementById('open-modal7').style.display = 'none';
    document.getElementById('open-modal8').style.display = 'none';
    document.getElementById('open-modal9').style.display = 'none';
    document.getElementById('open-modal10').style.display = 'none';
}
function downloadFile(fileUrl) {
    fetch(fileUrl)
      .then(response => response.blob())
      .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = fileUrl.split('/').pop();
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
      })
      .catch(error => console.error('Error downloading file:', error));
  }



