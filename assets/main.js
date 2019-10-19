function putRedirect() {
    var a = document.getElementById("url").value;
    if (a == '') {
        alert("Please enter a URL to shorten.");
        return
    }
    var data = null;
    var xhr = new XMLHttpRequest();
    xhr.withCredentials = true;
    xhr.addEventListener("readystatechange", function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var data = JSON.parse(xhr.response);
            console.log(data);
            if (data['Error']){
                alert(data['Error']);
                return
            } else {
                console.log("Shortened URL: "+data['URL']);
                document.getElementById("redirect-message").innerHTML = "Shortened URL: <a href='https://w1z0.xyz/short/link.php?a="+data['URL']+"' class='font'>https://w1z0.xyz/short/link.php?a="+data['URL']+"</a>";
            }
      }
    });
    xhr.open("GET", "https://w1z0.xyz/short/api/putRedirect.php?a="+a);
    xhr.send(data);
}