function checkValue() {
    if(!isNaN(document.getElementById("isbn").value))
    {
        if((document.getElementById("isbn").value).length==13)
        {
        document.getElementById("loadBook").disabled=false;
        }
        else
        {
            document.getElementById("loadBook").disabled=true;
        }
    }
    else
    {
        document.getElementById("loadBook").disabled=true;
    }
}

function getValues()
{
    url="https://openlibrary.org/api/books?bibkeys=ISBN:"+document.getElementById("isbn").value+"&jscmd=data&format=json";
    var request = new XMLHttpRequest();
    request.open('GET', url, true);
    request.onload = function() {
    if (this.status >= 200 && this.status < 400) {
        var data = JSON.parse(this.response);
        var property=Object.getOwnPropertyNames(data);
        if(property.length==0)
        {
            document.getElementById("book-title").value="";
            document.getElementById("author").value="";
            document.getElementById("loadBook").disabled=true;
        }
        else
        {
            document.getElementById("book-title").value=data[property[0]].title;
            document.getElementById("author").value=data[property[0]].authors[0].name;
            document.getElementById("loadBook").disabled=true;
        }
    } else {
        alert("Error!");
    }
    };
    request.onerror = function() {
    alert("Connection error!");
    };
    request.send();
}
