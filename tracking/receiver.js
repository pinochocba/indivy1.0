window.onload = function() {
	// Get a reference to the <div> on the page that will display the
	// message text.
	var messageEle = document.getElementById('message');

	// A function to process messages received by the window.
	function receiveMessage(e) {
		// Check to make sure that this message came from the correct domain.
		/*if (e.origin !== "http://www.southkom.com.ar")
			return;*/

		// Update the div element to display the message.
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == XMLHttpRequest.DONE ) {
           messageEle.innerHTML = "status: " + xmlhttp.status+", data: "+e.data;
        }
    };
	
    xmlhttp.open("POST", 'https://admin.indivy.com/form/submit', true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlhttp.send(e.data);
    
		
	}

	// Setup an event listener that calls receiveMessage() when the window
	// receives a new MessageEvent.
	window.addEventListener('message', receiveMessage);
}
