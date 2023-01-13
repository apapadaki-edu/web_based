document.addEventListener("load", () =>  {
let height;

  const sendPostMessage = () => {
    if (height !== document.getElementsByClassName("markdown-body")[0].offsetHeight) {
      height = document.getElementsByClassName("markdown-body")[0].offsetHeight;
      window.parent.postMessage({
        frameHeight: height
      }, '*');
      console.log(height);
    }
  }

  window.onload = () => sendPostMessage();
  window.onresize = () => sendPostMessage();
});
/*
used in README.md file for getting its height size and sending it to the hosting size (my main webpage),
in order to fix the size of the iframe on the parent hosting site the contains it.
*/ 

/*
remember to visit (https://github.com/ryanflorence/render-markdown-javascript/blob/master/README.md)
in order to find out how the embed js code into README.md files and look for a safer option
*/
