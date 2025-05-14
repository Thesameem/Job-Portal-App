/**
 ** this function set the cookie name with a value 
    for given time interval (expiry date) 

 * @param {String} name cookie name
 * @param {String} value value on the provided cookie name
 * @param {Number} expiry expiry date
*/

const setCookie = (name, value, expiry) => {
    const d = new Date();
    d.setTime(d.getTime() + (expiry * 24 * 60 * 60 * 1000));
    let expires = "expires="+d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
};

/**
 ** this function check for the cookie if stored 
    if found it will return the stored cookie return null if not stored on the name

 * @param {String} cookieName name of the cookie to get the value
 * @returns cookie value if stored, blank or null if not stored
 * 
*/

const getCookie = (cookieName) => {
    
    let name = cookieName + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }

    return false;
};

// Export both of the functions
export default {
    setCookie,
    getCookie
};