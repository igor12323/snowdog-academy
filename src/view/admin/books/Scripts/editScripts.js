function checkValue() {
    if(!isNaN(document.getElementById("isbn").value))
    {
        if((document.getElementById("isbn").value).length==13||(document.getElementById("isbn").value).length==10)
        {
        document.getElementById("loadBook").style.visibility="visible";
        }
        else
        {
            document.getElementById("loadBook").style.visibility="hidden";
        }
    }
    else
    {
        document.getElementById("loadBook").style.visibility="hidden";
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
    var propertyNames=Object.getOwnPropertyNames(data);
    if(propertyNames.length==0)
    {
        document.getElementById("book-title").value="";
        document.getElementById("author").value="";
        alert("Book with ISBN: "+document.getElementById("isbn").value+" doesn't exist!");
    }
    else
    {
        document.getElementById("book-title").value="";
        document.getElementById("author").value="";
        document.getElementById("book-title").value=data[propertyNames[0]].title;
        document.getElementById("author").value=data[propertyNames[0]].authors[0].name;
    }
} 
else {
    alert("Error!");
}
};
request.onerror = function() {
alert("Connection error!");
};
}