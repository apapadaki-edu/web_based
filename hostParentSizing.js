  let height;

  const sendPostMessage = () => {
    if (height !== document.getElementByClassName("markdown-body")[0].offsetHeight) {
      height = document.getElementByClassName("markdown-body")[0].offsetHeight;
      window.parent.postMessage({
        frameHeight: height
      }, '*');
      console.log(height);
    }
  }

  window.onload = () => sendPostMessage();
  window.onresize = () => sendPostMessage();
