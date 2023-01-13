  let height;

  const sendPostMessage = () => {
    if (height !== document.documentElement.offsetHeight) {
      height = document.documentElement.offsetHeight;
      window.parent.postMessage({
        frameHeight: height
      }, '*');
      console.log(height);
    }
  }

  window.onload = () => sendPostMessage();
  window.onresize = () => sendPostMessage();
