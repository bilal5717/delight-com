var script = document.createElement('script');
script.src = 'resources/views/layouts/load.js';

script.onload = function() {
  console.log('The load.js file has been loaded and executed successfully!');
  // Any additional code you want to run after the script has loaded
};

document.head.appendChild(script);


        // Get all the links on the page
        var links = document.getElementsByTagName("a");
        
        // Loop through the links and prefetch them
        for (var i = 0; i < links.length; i++) {
          var href = links[i].getAttribute("href");
          if (href && href.indexOf("http") === 0) {
            var link = document.createElement("link");
            link.setAttribute("rel", "prefetch");
            link.setAttribute("href", href);
            document.head.appendChild(link);
          }
        }
        
        // Event listener to handle the click event on the links
        document.addEventListener("click", function(event) {
          var target = event.target;
        
          // Check if the clicked element is a link
          if (target.tagName.toLowerCase() === "a") {
            var href = target.getAttribute("href");
        
            // Check if the link starts with "http"
            if (href && href.indexOf("http") === 0) {
              // Prefetch the link
              var link = document.createElement("link");
              link.setAttribute("rel", "prefetch");
              link.setAttribute("href", href);
              document.head.appendChild(link);
            }
          }
  
});
