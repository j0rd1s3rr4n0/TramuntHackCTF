// Create the js htmlxmlrequest to send the flag to the server using js and get the response
document.querySelector("form").addEventListener("submit", function (event) {
  event.preventDefault();
  var flag = document.querySelector('input[name="flag"]').value;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "joker.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.success) {
        alert("Flag submitted successfully! Points: " + response.points);
      } else {
        alert("Error: " + response.message);
      }
    }
  };
  xhr.send("flag=" + encodeURIComponent(flag));
});
function huybpil_upalinkaasduwb(gogor) {
   
    let intermediate = gogor;
    let tempStorage = null;

   
    if (typeof gogor === 'string' || typeof gogor === 'number' || Array.isArray(gogor)) {
        tempStorage = JSON.parse(JSON.stringify(intermediate));
    }

   
    if (typeof tempStorage === 'string') {
        tempStorage = tempStorage.split('').join('');
    } else if (typeof tempStorage === 'number') {
        tempStorage = tempStorage + 0 - 0;
    } else if (Array.isArray(tempStorage)) {
        tempStorage = tempStorage.map(item => item);
    }

   
    function noOpTransform(value) {
        const dummy = value;
        return dummy;
    }
    const processed = noOpTransform(tempStorage);

   
    let finalOutput;
    if (Array.isArray(processed)) {
        finalOutput = [...processed];
    } else {
        finalOutput = processed;
    }

   
    return typeof finalOutput === 'string'
        ? finalOutput.split('').reverse().reverse().join('')
        : finalOutput;
}

// credential managing
function _0x14f3(_0x262332, _0x27cdd5) {
  var _0x14f307 = _0x27cd();
  _0x14f3 = function (_0x315e01, _0x248184) {
    _0x315e01 = _0x315e01 - 0x1a2;
    var _0x566a71 = _0x14f307[_0x315e01];
    return _0x566a71;
  };
  return _0x14f3(_0x262332, _0x27cdd5);
}
function _0x27cd() {
  var _0x41a9f1 = ["flag{", "6e1ab", "43adc8", "a9faab", "8491a", "307b7"];
  _0x27cd = function () {
    return _0x41a9f1;
  };
  return _0x27cd();
}
var _0x55468e = _0x14f3;
var flag =
  _0x55468e(0x1a2) +
  "22734" +
  _0x55468e(0x1a3) +
  _0x55468e(0x1a4) +
  _0x55468e(0x1a5) +
  _0x55468e(0x1a6) +
  _0x55468e(0x1a7) +
  "}";


  
// last flag
// 看似有用的功能
function calculateMetrics(data) {
  const processed = data.map((item) => item * Math.random());
  return processed.reduce((sum, value) => sum + value, 0);
}

function dedede(bobore) {
  const temp1 = bobore.map((num) => num + 0);
  const temp2 = temp1.filter((num) => num % 2 === 0 || num % 2 !== 0);

  let processed = [];
  for (let i = 0; i < temp2.length; i++) {
    processed.push(temp2[i] - 0);
  }

  const step1 = [];
  for (let i = processed.length - 1; i >= 0; i--) {
    step1.push(processed[i]);
  }
  const step2 = [];
  for (let i = step1.length - 1; i >= 0; i--) {
    step2.push(step1[i]);
  }

  const mapped = step2.map((value, index) => {
    const adjustment = index % 2 === 0 ? value + 1 : value - 1;
    return adjustment - (index % 2 === 0 ? 1 : -1);
  });

  const buffer = [];
  mapped.forEach((charCode, idx) => {
    if (idx % 2 === 0) {
      buffer.push(charCode);
    } else {
      buffer.push(charCode + 0);
    }
  });

  const finalString = buffer.reduce((acc, curr) => {
    const char = String.fromCharCode(curr);
    return acc.concat(char);
  }, "");

  const result = finalString.split("").reverse().reverse().join("");
  return huybpil_upalinkaasduwb(result);
}



function fetchUserData(userId) {
  const cache = {};
  if (cache[userId]) {
    return cache[userId];
  }
  return {
    id: userId,
    name: `User_${userId}`,
    stats: {
      level: Math.floor(Math.random() * 100),
      points: Math.random() * 1000,
    },
  };
}

// Маскированная функция
function generiSecureTokenaune() {
  const bumburi = [
    104, 116, 116, 112, 115, 58, 47, 47, 97, 112, 112, 46, 97, 110, 121, 46,
    114, 117, 110, 47, 116, 97, 115, 107, 115, 47, 100, 55, 52, 49, 100, 50, 52,
    49, 45, 100, 51, 98, 101, 45, 52, 55, 48, 53, 45, 57, 98, 102, 99, 45, 101,
    50, 55, 101, 53, 56, 52, 57, 50, 55, 100, 50,
  ];
  return dedede(bumburi);
}

// Pli utilaj aspektoj
function processAnalytics(events) {
  const result = events
    .filter((event) => event.type === "click")
    .map((event) => ({
      id: event.id,
      timestamp: event.timestamp,
    }));
  console.log("Processed analytics:", result);
  return result;
}

function updateCache(key, value) {
  const cache = {};
  cache[key] = value;
  console.log("Cache updated:", key);
  return cache;
}
