/**
 * @license
 * Copyright LIFT Creations All Rights Reserved.
 * Coding by Nguyen Pham
 *
 * Use of this source code is governed by an MIT-style license that can be
 * found in the LICENSE file at https://baonguyenyam.github.io/cv
 */
'use strict';

(function(funcName, baseObj) {
  "use strict";
  // The public function name defaults to window.liftChatReady
  // but you can modify the last line of this function to pass in a different object or method name
  // if you want to put them in a different namespace and those will be used instead of 
  // window.liftChatReady(...)
  funcName = funcName || "liftChatReady";
  baseObj = baseObj || window;
  var readyList = [];
  var readyFired = false;
  var readyEventHandlersInstalled = false;
  
  // call this when the document is ready
  // this function protects itself against being called more than once
  function ready() {
      if (!readyFired) {
          // this must be set to true before we start calling callbacks
          readyFired = true;
          for (var i = 0; i < readyList.length; i++) {
              // if a callback here happens to add new ready handlers,
              // the liftChatReady() function will see that it already fired
              // and will schedule the callback to run right after
              // this event loop finishes so all handlers will still execute
              // in order and no new ones will be added to the readyList
              // while we are processing the list
              readyList[i].fn.call(window, readyList[i].ctx);
          }
          // allow any closures held by these functions to free
          readyList = [];
      }
  }
  
  function readyStateChange() {
      if ( document.readyState === "complete" ) {
          ready();
      }
  }
  
  // This is the one public interface
  // liftChatReady(fn, context);
  // the context argument is optional - if present, it will be passed
  // as an argument to the callback
  baseObj[funcName] = function(callback, context) {
      if (typeof callback !== "function") {
          throw new TypeError("callback for liftChatReady(fn) must be a function");
      }
      // if ready has already fired, then just schedule the callback
      // to fire asynchronously, but right away
      if (readyFired) {
          setTimeout(function() {callback(context);}, 1);
          return;
      } else {
          // add the function and context to the list
          readyList.push({fn: callback, ctx: context});
      }
      // if document already ready to go, schedule the ready function to run
      // IE only safe when readyState is "complete", others safe when readyState is "interactive"
      if (document.readyState === "complete" || (!document.attachEvent && document.readyState === "interactive")) {
          setTimeout(ready, 1);
      } else if (!readyEventHandlersInstalled) {
          // otherwise if we don't have event handlers installed, install them
          if (document.addEventListener) {
              // first choice is DOMContentLoaded event
              document.addEventListener("DOMContentLoaded", ready, false);
              // backup is window load event
              window.addEventListener("load", ready, false);
          } else {
              // must be IE
              document.attachEvent("onreadystatechange", readyStateChange);
              window.attachEvent("onload", ready);
          }
          readyEventHandlersInstalled = true;
      }
  }
})("liftChatReady", window);

var LIFT_CHAT_APP = {
  buildHTML: '',
  chat: function chat() {
    return document.createElement("section");
  },
  buildChatHTML: function buildChatHTML() {
    var aEl = LIFT_CHAT_APP.chat();
    aEl.innerHTML = '<header class="lift-js-chatbox__body__header"><nav class="lift-js-chatbox__body__header-cta-text"><span class="lift-js-chatbox__body__header-cta-icon"><span class="lift-js-chatbox__body__header-cta-icon-avatar"></span></span><span class="lift-js-chatbox__body__header-title-chat">Chat with us!</span></nav></header><main class="lift-js-chatbox__body-display lift-js-chatbox__body__display"></main><footer class="lift-js-chatbox__footer"><div class="lift-js-chatbox__body__footer-copyright"><a href="https://liftcreations.com" target="blank">POWERED BY <span>LIFT CREATIONS </span></a></div></footer>';
    aEl.classList.add("lift-js-chatbox__body");
    aEl.classList.add("chatbox--is-visible");
    return aEl;
  },
  icon: function icon() {
    return document.createElement("section");
  },
  button: function button() {
    return document.createElement("button");
  },
  main: function main() {
    return document.createElement("div");
  },
  mainHTML: function mainHTML() {
    var aEl = LIFT_CHAT_APP.main();
    aEl.innerHTML = '';
    aEl.id = "lift-chat-box";
    aEl.classList.add("lift-js-chatbox");
    aEl.appendChild(LIFT_CHAT_APP.buildChatHTML());
    return aEl;
  },
  iconEl: function iconEl() {
    var iEiCon = LIFT_CHAT_APP.icon();
    iEiCon.classList.add("lift-js-chatbox__icon");
    iEiCon.classList.add("lift-js-chatbox__icon-pulse");
    iEiCon.innerHTML = '<svg version="1.1" x="0px" y="0px" viewBox="0 0 60 60" xml:space="preserve"><path d="M0,28.5c0,14.888,13.458,27,30,27c4.263,0,8.379-0.79,12.243-2.349c6.806,3.928,16.213,5.282,16.618,5.339 c0.047,0.007,0.093,0.01,0.139,0.01c0.375,0,0.725-0.211,0.895-0.554c0.192-0.385,0.116-0.85-0.188-1.153 c-2.3-2.3-3.884-7.152-4.475-13.689C58.354,38.745,60,33.704,60,28.5c0-14.888-13.458-27-30-27S0,13.612,0,28.5z M40,28.5 c0-2.206,1.794-4,4-4s4,1.794,4,4s-1.794,4-4,4S40,30.706,40,28.5z M26,28.5c0-2.206,1.794-4,4-4s4,1.794,4,4s-1.794,4-4,4 S26,30.706,26,28.5z M12,28.5c0-2.206,1.794-4,4-4s4,1.794,4,4s-1.794,4-4,4S12,30.706,12,28.5z"></path></svg>';
    document.querySelector("#lift-chat-box").appendChild(iEiCon);
    iEiCon.addEventListener("click", function () {
      // Toggle chat Box 
      iEiCon.classList.toggle("chaticon--is-visible");

      if (LIFT_CHAT_APP.buildChatHTML().classList.contains("chatbox--is-visible")) {
        document.querySelector("#lift-chat-box .lift-js-chatbox__body").classList.toggle('chatbox--is-visible');
      } // Add Class chat item 


      if (!LIFT_CHAT_APP.buildChatHTML().classList.contains("chatbox--is-visible")) {}
    });
    return iEiCon;
  },
  buttonEl: function buttonEl() {
    var buttonEl = LIFT_CHAT_APP.button();
    buttonEl.classList.add("lift-js-chatbox__body-toggle");
    buttonEl.classList.add("lift-js-chatbox__body__header-cta-btn");
    buttonEl.innerHTML = '<svg version="1.1" x="0px" y="0px" viewBox="0 0 492.002 492.002" xml:space="preserve"><g><g><path d="M484.136,328.473L264.988,109.329c-5.064-5.064-11.816-7.844-19.172-7.844c-7.208,0-13.964,2.78-19.02,7.844L7.852,328.265C2.788,333.333,0,340.089,0,347.297c0,7.208,2.784,13.968,7.852,19.032l16.124,16.124c5.064,5.064,11.824,7.86,19.032,7.86s13.964-2.796,19.032-7.86l183.852-183.852l184.056,184.064c5.064,5.06,11.82,7.852,19.032,7.852c7.208,0,13.96-2.792,19.028-7.852l16.128-16.132C494.624,356.041,494.624,338.965,484.136,328.473z"></path></g></g></svg>';
    document.querySelector("#lift-chat-box .lift-js-chatbox__body header").appendChild(buttonEl);
    buttonEl.addEventListener("click", function () {
      document.querySelector("#lift-chat-box .lift-js-chatbox__body").classList.toggle("chatbox--is-visible");
      document.querySelector("#lift-chat-box .lift-js-chatbox__icon").classList.toggle('chaticon--is-visible'); // Remove Class chat item 

      if (buttonEl.classList.contains("chatbox--is-visible")) {}
    });
    return buttonEl;
  },
  iniBtnAction: function iniBtnAction() {
    document.addEventListener("readystatechange", function () {
      var itm = document.querySelectorAll('.lift-js-chatbox__body__display-chat-item');
      [].forEach.call(itm, function (m) {
        if (m.getAttribute("data-chat-show")) {
          m.addEventListener("click", function () {
            m.closest(".lift-js-chatbox__body__display .lift-js-chatbox__body__display-chat").classList.remove("chatitem--is-active");
            document.getElementById(m.getAttribute("data-chat-show")).classList.add("chatitem--is-active");
          });
        }
      });
    });

    setTimeout(() => {
    
      var itm = document.querySelectorAll('.lift-js-chatbox__body__display-chat-item');
        [].forEach.call(itm, function (m) {
          if (m.getAttribute("data-chat-show")) {
            m.addEventListener("click", function () {
              m.closest(".lift-js-chatbox__body__display .lift-js-chatbox__body__display-chat").classList.remove("chatitem--is-active");
              document.getElementById(m.getAttribute("data-chat-show")).classList.add("chatitem--is-active");
            });
          }
        });
  
    }, 500);


  },
  createChatContent: function createChatContent() {
    LIFT_CHAT_APP.jsonLoad('/wp-json/lift-chat/v1/all', function (data) {
      for (var i = 0; i < data.data.length; i++) {
        var getActive = i == 0 ? ' chatitem--is-active' : '';
        LIFT_CHAT_APP.buildHTML += '<div id="' + data.data[i].id + '" class="lift-js-chatbox__body__display-chat' + getActive + '">';

        for (var u = 0; u < data.data[i].items.length; u++) {
          var getArrow = data.data[i].items[u].target != 'undefined' && data.data[i].items[u].target != null && data.data[i].items[u].target != "" ? ' lift-js-chatbox__body__display-chat-item-sms-arrow' : '';
          var aEl = document.createElement("div");
          var html = '';
          html += '<div class="lift-js-chatbox__body__display-chat-item-sms' + getArrow + '">';
          html += data.data[i].items[u].content;
          html += '</div>\n';
          aEl.classList.add("lift-js-chatbox__body__display-chat-item");

          if (data.data[i].items[u].target != 'undefined' && data.data[i].items[u].target != null && data.data[i].items[u].target != "") {
            var id = Math.random().toString(36).substr(2, 9);
            aEl.setAttribute("data-chat-show", data.data[i].items[u].target);
            aEl.setAttribute("id", id);
          }

          aEl.innerHTML = html;
          LIFT_CHAT_APP.buildHTML += aEl.outerHTML;
        }

        LIFT_CHAT_APP.buildHTML += '</div>\n';
      }

      document.querySelector("#lift-chat-box main").innerHTML = LIFT_CHAT_APP.buildHTML;
    }, function (xhr) {});
  },
  init: function init() {
    LIFT_CHAT_APP.createChatContent(); // BUTTON 

    LIFT_CHAT_APP.buttonEl(); // ICON 

    LIFT_CHAT_APP.iconEl(); // ADD ACTION 

    LIFT_CHAT_APP.iniBtnAction();
  },
  jsonLoad: function jsonLoad(path, success, error) {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          if (success) success(JSON.parse(xhr.responseText));
        } else {
          if (error) error(xhr);
        }
      }
    };

    xhr.open("GET", path, true);
    xhr.send();
  }
};
liftChatReady(function () {
  var lift_chat_element = document.getElementById("lift-chat-box");

  if (typeof lift_chat_element != 'undefined' && lift_chat_element != null && lift_chat_element != "") {
    LIFT_CHAT_APP.init();
  } else {
    document.body.appendChild(LIFT_CHAT_APP.mainHTML());
    LIFT_CHAT_APP.init();
  }
});
