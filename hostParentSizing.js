  let height;

  const sendPostMessage = () => {
    if (height !== document.getElementById('container').offsetHeight) {
      height = document.getElementById('container').offsetHeight;
      window.parent.postMessage({
        frameHeight: height
      }, '*');
      console.log(height);
    }
  }

  window.onload = () => sendPostMessage();
  window.onresize = () => sendPostMessage();
