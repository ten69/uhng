!function(e){if("object"==typeof exports&&"undefined"!=typeof module)module.exports=e();else if("function"==typeof define&&define.amd)define([],e);else{var f;"undefined"!=typeof window?f=window:"undefined"!=typeof global?f=global:"undefined"!=typeof self&&(f=self),f.io=e()}}(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(_dereq_,module,exports){

module.exports = _dereq_('https://cdn4.tgdd.vn/v2015/Scripts/desktop/V5/lib/');

},{"./lib/":2}],2:[function(_dereq_,module,exports){

/**
 * Module dependencies.
 */

var url = _dereq_('./url');
var parser = _dereq_('socket.io-parser');
var Manager = _dereq_('./manager');
var debug = _dereq_('debug')('socket.io-client');

/**
 * Module exports.
 */

module.exports = exports = lookup;

/**
 * Managers cache.
 */

var cache = exports.managers = {};

/**
 * Looks up an existing `Manager` for multiplexing.
 * If the user summons:
 *
 *   `io('http://localhost/a');`
 *   `io('http://localhost/b');`
 *
 * We reuse the existing instance based on same scheme/port/host,
 * and we initialize sockets for each namespace.
 *
 * @api public
 */

function lookup(uri, opts) {
  if (typeof uri == 'object') {
    opts = uri;
    uri = undefined;
  }

  opts = opts || {};

  var parsed = url(https://cdn4.tgdd.vn/v2015/Scripts/desktop/V5/uri);
  var source = parsed.source;
  var id = parsed.id;
  var io;

  if (opts.forceNew || opts['force new connection'] || false === opts.multiplex) {
    debug('ignoring socket cache for %s', source);
    io = Manager(source, opts);
  } else {
    if (!cache[id]) {
      debug('new io instance for %s', source);
      cache[id] = Manager(source, opts);
    }
    io = cache[id];
  }

  return io.socket(parsed.path);
}

/**
 * Protocol version.
 *
 * @api public
 */

exports.protocol = parser.protocol;

/**
 * `connect`.
 *
 * @param {String} uri
 * @api public
 */

exports.connect = lookup;

/**
 * Expose constructors for standalone build.
 *
 * @api public
 */

exports.Manager = _dereq_('./manager');
exports.Socket = _dereq_('./socket');

},{"./manager":3,"./socket":5,"./url":6,"debug":10,"socket.io-parser":46}],3:[function(_dereq_,module,exports){

/**
 * Module dependencies.
 */

var url = _dereq_('./url');
var eio = _dereq_('engine.io-client');
var Socket = _dereq_('./socket');
var Emitter = _dereq_('component-emitter');
var parser = _dereq_('socket.io-parser');
var on = _dereq_('./on');
var bind = _dereq_('component-bind');
var object = _dereq_('object-component');
var debug = _dereq_('debug')('socket.io-client:manager');
var indexOf = _dereq_('indexof');
var Backoff = _dereq_('backo2');

/**
 * Module exports
 */

module.exports = Manager;

/**
 * `Manager` constructor.
 *
 * @param {String} engine instance or engine uri/opts
 * @param {Object} options
 * @api public
 */

function Manager(uri, opts){
  if (!(this instanceof Manager)) return new Manager(uri, opts);
  if (uri && ('object' == typeof uri)) {
    opts = uri;
    uri = undefined;
  }
  opts = opts || {};

  opts.path = opts.path || '/socket.io';
  this.nsps = {};
  this.subs = [];
  this.opts = opts;
  this.reconnection(opts.reconnection !== false);
  this.reconnectionAttempts(opts.reconnectionAttempts || Infinity);
  this.reconnectionDelay(opts.reconnectionDelay || 1000);
  this.reconnectionDelayMax(opts.reconnectionDelayMax || 5000);
  this.randomizationFactor(opts.randomizationFactor || 0.5);
  this.backoff = new Backoff({
    min: this.reconnectionDelay(),
    max: this.reconnectionDelayMax(),
    jitter: this.randomizationFactor()
  });
  this.timeout(null == opts.timeout ? 20000 : opts.timeout);
  this.readyState = 'closed';
  this.uri = uri;
  this.connected = [];
  this.encoding = false;
  this.packetBuffer = [];
  this.encoder = new parser.Encoder();
  this.decoder = new parser.Decoder();
  this.autoConnect = opts.autoConnect !== false;
  if (this.autoConnect) this.open();
}

/**
 * Propagate given event to sockets and emit on `this`
 *
 * @api private
 */

Manager.prototype.emitAll = function() {
  this.emit.apply(this, arguments);
  for (var nsp in this.nsps) {
    this.nsps[nsp].emit.apply(this.nsps[nsp], arguments);
  }
};

/**
 * Update `socket.id` of all sockets
 *
 * @api private
 */

Manager.prototype.updateSocketIds = function(){
  for (var nsp in this.nsps) {
    this.nsps[nsp].id = this.engine.id;
  }
};

/**
 * Mix in `Emitter`.
 */

Emitter(Manager.prototype);

/**
 * Sets the `reconnection` config.
 *
 * @param {Boolean} true/false if it should automatically reconnect
 * @return {Manager} self or value
 * @api public
 */

Manager.prototype.reconnection = function(v){
  if (!arguments.length) return this._reconnection;
  this._reconnection = !!v;
  return this;
};

/**
 * Sets the reconnection attempts config.
 *
 * @param {Number} max reconnection attempts before giving up
 * @return {Manager} self or value
 * @api public
 */

Manager.prototype.reconnectionAttempts = function(v){
  if (!arguments.length) return this._reconnectionAttempts;
  this._reconnectionAttempts = v;
  return this;
};

/**
 * Sets the delay between reconnections.
 *
 * @param {Number} delay
 * @return {Manager} self or value
 * @api public
 */

Manager.prototype.reconnectionDelay = function(v){
  if (!arguments.length) return this._reconnectionDelay;
  this._reconnectionDelay = v;
  this.backoff && this.backoff.setMin(v);
  return this;
};

Manager.prototype.randomizationFactor = function(v){
  if (!arguments.length) return this._randomizationFactor;
  this._randomizationFactor = v;
  this.backoff && this.backoff.setJitter(v);
  return this;
};

/**
 * Sets the maximum delay between reconnections.
 *
 * @param {Number} delay
 * @return {Manager} self or value
 * @api public
 */

Manager.prototype.reconnectionDelayMax = function(v){
  if (!arguments.length) return this._reconnectionDelayMax;
  this._reconnectionDelayMax = v;
  this.backoff && this.backoff.setMax(v);
  return this;
};

/**
 * Sets the connection timeout. `false` to disable
 *
 * @return {Manager} self or value
 * @api public
 */

Manager.prototype.timeout = function(v){
  if (!arguments.length) return this._timeout;
  this._timeout = v;
  return this;
};

/**
 * Starts trying to reconnect if reconnection is enabled and we have not
 * started reconnecting yet
 *
 * @api private
 */

Manager.prototype.maybeReconnectOnOpen = function() {
  // Only try to reconnect if it's the first time we're connecting
  if (!this.reconnecting && this._reconnection && this.backoff.attempts === 0) {
    // keeps reconnection from firing twice for the same reconnection loop
    this.reconnect();
  }
};


/**
 * Sets the current transport `socket`.
 *
 * @param {Function} optional, callback
 * @return {Manager} self
 * @api public
 */

Manager.prototype.open =
Manager.prototype.connect = function(fn){
  debug('readyState %s', this.readyState);
  if (~this.readyState.indexOf('open')) return this;

  debug('opening %s', this.uri);
  this.engine = eio(this.uri, this.opts);
  var socket = this.engine;
  var self = this;
  this.readyState = 'opening';
  this.skipReconnect = false;

  // emit `open`
  var openSub = on(socket, 'open', function() {
    self.onopen();
    fn && fn();
  });

  // emit `connect_error`
  var errorSub = on(socket, 'error', function(data){
    debug('connect_error');
    self.cleanup();
    self.readyState = 'closed';
    self.emitAll('connect_error', data);
    if (fn) {
      var err = new Error('Connection error');
      err.data = data;
      fn(err);
    } else {
      // Only do this if there is no fn to handle the error
      self.maybeReconnectOnOpen();
    }
  });

  // emit `connect_timeout`
  if (false !== this._timeout) {
    var timeout = this._timeout;
    debug('connect attempt will timeout after %d', timeout);

    // set timer
    var timer = setTimeout(function(){
      debug('connect attempt timed out after %d', timeout);
      openSub.destroy();
      socket.close();
      socket.emit('error', 'timeout');
      self.emitAll('connect_timeout', timeout);
    }, timeout);

    this.subs.push({
      destroy: function(){
        clearTimeout(timer);
      }
    });
  }

  this.subs.push(openSub);
  this.subs.push(errorSub);

  return this;
};

/**
 * Called upon transport open.
 *
 * @api private
 */

Manager.prototype.onopen = function(){
  debug('open');

  // clear old subs
  this.cleanup();

  // mark as open
  this.readyState = 'open';
  this.emit('open');

  // add new subs
  var socket = this.engine;
  this.subs.push(on(socket, 'data', bind(this, 'ondata')));
  this.subs.push(on(this.decoder, 'decoded', bind(this, 'ondecoded')));
  this.subs.push(on(socket, 'error', bind(this, 'onerror')));
  this.subs.push(on(socket, 'close', bind(this, 'onclose')));
};

/**
 * Called with data.
 *
 * @api private
 */

Manager.prototype.ondata = function(data){
  this.decoder.add(data);
};

/**
 * Called when parser fully decodes a packet.
 *
 * @api private
 */

Manager.prototype.ondecoded = function(packet) {
  this.emit('packet', packet);
};

/**
 * Called upon socket error.
 *
 * @api private
 */

Manager.prototype.onerror = function(err){
  debug('error', err);
  this.emitAll('error', err);
};

/**
 * Creates a new socket for the given `nsp`.
 *
 * @return {Socket}
 * @api public
 */

Manager.prototype.socket = function(nsp){
  var socket = this.nsps[nsp];
  if (!socket) {
    socket = new Socket(this, nsp);
    this.nsps[nsp] = socket;
    var self = this;
    socket.on('connect', function(){
      socket.id = self.engine.id;
      if (!~indexOf(self.connected, socket)) {
        self.connected.push(socket);
      }
    });
  }
  return socket;
};

/**
 * Called upon a socket close.
 *
 * @param {Socket} socket
 */

Manager.prototype.destroy = function(socket){
  var index = indexOf(this.connected, socket);
  if (~index) this.connected.splice(index, 1);
  if (this.connected.length) return;

  this.close();
};

/**
 * Writes a packet.
 *
 * @param {Object} packet
 * @api private
 */

Manager.prototype.packet = function(packet){
  debug('writing packet %j', packet);
  var self = this;

  if (!self.encoding) {
    // encode, then write to engine with result
    self.encoding = true;
    this.encoder.encode(packet, function(encodedPackets) {
      for (var i = 0; i < encodedPackets.length; i++) {
        self.engine.write(encodedPackets[i]);
      }
      self.encoding = false;
      self.processPacketQueue();
    });
  } else { // add packet to the queue
    self.packetBuffer.push(packet);
  }
};

/**
 * If packet buffer is non-empty, begins encoding the
 * next packet in line.
 *
 * @api private
 */

Manager.prototype.processPacketQueue = function() {
  if (this.packetBuffer.length > 0 && !this.encoding) {
    var pack = this.packetBuffer.shift();
    this.packet(pack);
  }
};

/**
 * Clean up transport subscriptions and packet buffer.
 *
 * @api private
 */

Manager.prototype.cleanup = function(){
  var sub;
  while (sub = this.subs.shift()) sub.destroy();

  this.packetBuffer = [];
  this.encoding = false;

  this.decoder.destroy();
};

/**
 * Close the current socket.
 *
 * @api private
 */

Manager.prototype.close =
Manager.prototype.disconnect = function(){
  this.skipReconnect = true;
  this.backoff.reset();
  this.readyState = 'closed';
  this.engine && this.engine.close();
};

/**
 * Called upon engine close.
 *
 * @api private
 */

Manager.prototype.onclose = function(reason){
  debug('close');
  this.cleanup();
  this.backoff.reset();
  this.readyState = 'closed';
  this.emit('close', reason);
  if (this._reconnection && !this.skipReconnect) {
    this.reconnect();
  }
};

/**
 * Attempt a reconnection.
 *
 * @api private
 */

Manager.prototype.reconnect = function(){
  if (this.reconnecting || this.skipReconnect) return this;

  var self = this;

  if (this.backoff.attempts >= this._reconnectionAttempts) {
    debug('reconnect failed');
    this.backoff.reset();
    this.emitAll('reconnect_failed');
    this.reconnecting = false;
  } else {
    var delay = this.backoff.duration();
    debug('will wait %dms before reconnect attempt', delay);

    this.reconnecting = true;
    var timer = setTimeout(function(){
      if (self.skipReconnect) return;

      debug('attempting reconnect');
      self.emitAll('reconnect_attempt', self.backoff.attempts);
      self.emitAll('reconnecting', self.backoff.attempts);

      // check again for the case socket closed in above events
      if (self.skipReconnect) return;

      self.open(function(err){
        if (err) {
          debug('reconnect attempt error');
          self.reconnecting = false;
          self.reconnect();
          self.emitAll('reconnect_error', err.data);
        } else {
          debug('reconnect success');
          self.onreconnect();
        }
      });
    }, delay);

    this.subs.push({
      destroy: function(){
        clearTimeout(timer);
      }
    });
  }
};

/**
 * Called upon successful reconnect.
 *
 * @api private
 */

Manager.prototype.onreconnect = function(){
  var attempt = this.backoff.attempts;
  this.reconnecting = false;
  this.backoff.reset();
  this.updateSocketIds();
  this.emitAll('reconnect', attempt);
};

},{"./on":4,"./socket":5,"./url":6,"backo2":7,"component-bind":8,"component-emitter":9,"debug":10,"engine.io-client":11,"indexof":42,"object-component":43,"socket.io-parser":46}],4:[function(_dereq_,module,exports){

/**
 * Module exports.
 */

module.exports = on;

/**
 * Helper for subscriptions.
 *
 * @param {Object|EventEmitter} obj with `Emitter` mixin or `EventEmitter`
 * @param {String} event name
 * @param {Function} callback
 * @api public
 */

function on(obj, ev, fn) {
  obj.on(ev, fn);
  return {
    destroy: function(){
      obj.removeListener(ev, fn);
    }
  };
}

},{}],5:[function(_dereq_,module,exports){

/**
 * Module dependencies.
 */

var parser = _dereq_('socket.io-parser');
var Emitter = _dereq_('component-emitter');
var toArray = _dereq_('to-array');
var on = _dereq_('./on');
var bind = _dereq_('component-bind');
var debug = _dereq_('debug')('socket.io-client:socket');
var hasBin = _dereq_('has-binary');

/**
 * Module exports.
 */

module.exports = exports = Socket;

/**
 * Internal events (blacklisted).
 * These events can't be emitted by the user.
 *
 * @api private
 */

var events = {
  connect: 1,
  connect_error: 1,
  connect_timeout: 1,
  disconnect: 1,
  error: 1,
  reconnect: 1,
  reconnect_attempt: 1,
  reconnect_failed: 1,
  reconnect_error: 1,
  reconnecting: 1
};

/**
 * Shortcut to `Emitter#emit`.
 */

var emit = Emitter.prototype.emit;

/**
 * `Socket` constructor.
 *
 * @api public
 */

function Socket(io, nsp){
  this.io = io;
  this.nsp = nsp;
  this.json = this; // compat
  this.ids = 0;
  this.acks = {};
  if (this.io.autoConnect) this.open();
  this.receiveBuffer = [];
  this.sendBuffer = [];
  this.connected = false;
  this.disconnected = true;
}

/**
 * Mix in `Emitter`.
 */

Emitter(Socket.prototype);

/**
 * Subscribe to open, close and packet events
 *
 * @api private
 */

Socket.prototype.subEvents = function() {
  if (this.subs) return;

  var io = this.io;
  this.subs = [
    on(io, 'open', bind(this, 'onopen')),
    on(io, 'packet', bind(this, 'onpacket')),
    on(io, 'close', bind(this, 'onclose'))
  ];
};

/**
 * "Opens" the socket.
 *
 * @api public
 */

Socket.prototype.open =
Socket.prototype.connect = function(){
  if (this.connected) return this;

  this.subEvents();
  this.io.open(); // ensure open
  if ('open' == this.io.readyState) this.onopen();
  return this;
};

/**
 * Sends a `message` event.
 *
 * @return {Socket} self
 * @api public
 */

Socket.prototype.send = function(){
  var args = toArray(arguments);
  args.unshift('message');
  this.emit.apply(this, args);
  return this;
};

/**
 * Override `emit`.
 * If the event is in `events`, it's emitted normally.
 *
 * @param {String} event name
 * @return {Socket} self
 * @api public
 */

Socket.prototype.emit = function(ev){
  if (events.hasOwnProperty(ev)) {
    emit.apply(this, arguments);
    return this;
  }

  var args = toArray(arguments);
  var parserType = parser.EVENT; // default
  if (hasBin(args)) { parserType = parser.BINARY_EVENT; } // binary
  var packet = { type: parserType, data: args };

  // event ack callback
  if ('function' == typeof args[args.length - 1]) {
    debug('emitting packet with ack id %d', this.ids);
    this.acks[this.ids] = args.pop();
    packet.id = this.ids++;
  }

  if (this.connected) {
    this.packet(packet);
  } else {
    this.sendBuffer.push(packet);
  }

  return this;
};

/**
 * Sends a packet.
 *
 * @param {Object} packet
 * @api private
 */

Socket.prototype.packet = function(packet){
  packet.nsp = this.nsp;
  this.io.packet(packet);
};

/**
 * Called upon engine `open`.
 *
 * @api private
 */

Socket.prototype.onopen = function(){
  debug('transport is open - connecting');

  // write connect packet if necessary
  if ('/' != this.nsp) {
    this.packet({ type: parser.CONNECT });
  }
};

/**
 * Called upon engine `close`.
 *
 * @param {String} reason
 * @api private
 */

Socket.prototype.onclose = function(reason){
  debug('close (%s)', reason);
  this.connected = false;
  this.disconnected = true;
  delete this.id;
  this.emit('disconnect', reason);
};

/**
 * Called with socket packet.
 *
 * @param {Object} packet
 * @api private
 */

Socket.prototype.onpacket = function(packet){
  if (packet.nsp != this.nsp) return;

  switch (packet.type) {
    case parser.CONNECT:
      this.onconnect();
      break;

    case parser.EVENT:
      this.onevent(packet);
      break;

    case parser.BINARY_EVENT:
      this.onevent(packet);
      break;

    case parser.ACK:
      this.onack(packet);
      break;

    case parser.BINARY_ACK:
      this.onack(packet);
      break;

    case parser.DISCONNECT:
      this.ondisconnect();
      break;

    case parser.ERROR:
      this.emit('error', packet.data);
      break;
  }
};

/**
 * Called upon a server event.
 *
 * @param {Object} packet
 * @api private
 */

Socket.prototype.onevent = function(packet){
  var args = packet.data || [];
  debug('emitting event %j', args);

  if (null != packet.id) {
    debug('attaching ack callback to event');
    args.push(this.ack(packet.id));
  }

  if (this.connected) {
    emit.apply(this, args);
  } else {
    this.receiveBuffer.push(args);
  }
};

/**
 * Produces an ack callback to emit with an event.
 *
 * @api private
 */

Socket.prototype.ack = function(id){
  var self = this;
  var sent = false;
  return function(){
    // prevent double callbacks
    if (sent) return;
    sent = true;
    var args = toArray(arguments);
    debug('sending ack %j', args);

    var type = hasBin(args) ? parser.BINARY_ACK : parser.ACK;
    self.packet({
      type: type,
      id: id,
      data: args
    });
  };
};

/**
 * Called upon a server acknowlegement.
 *
 * @param {Object} packet
 * @api private
 */

Socket.prototype.onack = function(packet){
  debug('calling ack %s with %j', packet.id, packet.data);
  var fn = this.acks[packet.id];
  fn.apply(this, packet.data);
  delete this.acks[packet.id];
};

/**
 * Called upon server connect.
 *
 * @api private
 */

Socket.prototype.onconnect = function(){
  this.connected = true;
  this.disconnected = false;
  this.emit('connect');
  this.emitBuffered();
};

/**
 * Emit buffered events (received and emitted).
 *
 * @api private
 */

Socket.prototype.emitBuffered = function(){
  var i;
  for (i = 0; i < this.receiveBuffer.length; i++) {
    emit.apply(this, this.receiveBuffer[i]);
  }
  this.receiveBuffer = [];

  for (i = 0; i < this.sendBuffer.length; i++) {
    this.packet(this.sendBuffer[i]);
  }
  this.sendBuffer = [];
};

/**
 * Called upon server disconnect.
 *
 * @api private
 */

Socket.prototype.ondisconnect = function(){
  debug('server disconnect (%s)', this.nsp);
  this.destroy();
  this.onclose('io server disconnect');
};

/**
 * Called upon forced client/server side disconnections,
 * this method ensures the manager stops tracking us and
 * that reconnections don't get triggered for this.
 *
 * @api private.
 */

Socket.prototype.destroy = function(){
  if (this.subs) {
    // clean subscriptions to avoid reconnections
    for (var i = 0; i < this.subs.length; i++) {
      this.subs[i].destroy();
    }
    this.subs = null;
  }

  this.io.destroy(this);
};

/**
 * Disconnects the socket manually.
 *
 * @return {Socket} self
 * @api public
 */

Socket.prototype.close =
Socket.prototype.disconnect = function(){
  if (this.connected) {
    debug('performing disconnect (%s)', this.nsp);
    this.packet({ type: parser.DISCONNECT });
  }

  // remove socket from pool
  this.destroy();

  if (this.connected) {
    // fire events
    this.onclose('io client disconnect');
  }
  return this;
};

},{"./on":4,"component-bind":8,"component-emitter":9,"debug":10,"has-binary":38,"socket.io-parser":46,"to-array":50}],6:[function(_dereq_,module,exports){
(function (global){

/**
 * Module dependencies.
 */

var parseuri = _dereq_('parseuri');
var debug = _dereq_('debug')('socket.io-client:url');

/**
 * Module exports.
 */

module.exports = url;

/**
 * URL parser.
 *
 * @param {String} url
 * @param {Object} An object meant to mimic window.location.
 *                 Defaults to window.location.
 * @api public
 */

function url(https://cdn4.tgdd.vn/v2015/Scripts/desktop/V5/uri, loc){
  var obj = uri;

  // default to window.location
  var loc = loc || global.location;
  if (null == uri) uri = loc.protocol + '//' + loc.host;

  // relative path support
  if ('string' == typeof uri) {
    if ('/' == uri.charAt(0)) {
      if ('/' == uri.charAt(1)) {
        uri = loc.protocol + uri;
      } else {
        uri = loc.hostname + uri;
      }
    }

    if (!/^(https?|wss?):\/\//.test(uri)) {
      debug('protocol-less url %s', uri);
      if ('undefined' != typeof loc) {
        uri = loc.protocol + '//' + uri;
      } else {
        uri = 'https://' + uri;
      }
    }

    // parse
    debug('parse %s', uri);
    obj = parseuri(uri);
  }

  // make sure we treat `localhost:80` and `localhost` equally
  if (!obj.port) {
    if (/^(http|ws)$/.test(obj.protocol)) {
      obj.port = '80';
    }
    else if (/^(http|ws)s$/.test(obj.protocol)) {
      obj.port = '443';
    }
  }

  obj.path = obj.path || '/';

  // define unique id
  obj.id = obj.protocol + '://' + obj.host + ':' + obj.port;
  // define href
  obj.href = obj.protocol + '://' + obj.host + (loc && loc.port == obj.port ? '' : (':' + obj.port));

  return obj;
}

}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"debug":10,"parseuri":44}],7:[function(_dereq_,module,exports){

/**
 * Expose `Backoff`.
 */

module.exports = Backoff;

/**
 * Initialize backoff timer with `opts`.
 *
 * - `min` initial timeout in milliseconds [100]
 * - `max` max timeout [10000]
 * - `jitter` [0]
 * - `factor` [2]
 *
 * @param {Object} opts
 * @api public
 */

function Backoff(opts) {
  opts = opts || {};
  this.ms = opts.min || 100;
  this.max = opts.max || 10000;
  this.factor = opts.factor || 2;
  this.jitter = opts.jitter > 0 && opts.jitter <= 1 ? opts.jitter : 0;
  this.attempts = 0;
}

/**
 * Return the backoff duration.
 *
 * @return {Number}
 * @api public
 */

Backoff.prototype.duration = function(){
  var ms = this.ms * Math.pow(this.factor, this.attempts++);
  if (this.jitter) {
    var rand =  Math.random();
    var deviation = Math.floor(rand * this.jitter * ms);
    ms = (Math.floor(rand * 10) & 1) == 0  ? ms - deviation : ms + deviation;
  }
  return Math.min(ms, this.max) | 0;
};

/**
 * Reset the number of attempts.
 *
 * @api public
 */

Backoff.prototype.reset = function(){
  this.attempts = 0;
};

/**
 * Set the minimum duration
 *
 * @api public
 */

Backoff.prototype.setMin = function(min){
  this.ms = min;
};

/**
 * Set the maximum duration
 *
 * @api public
 */

Backoff.prototype.setMax = function(max){
  this.max = max;
};

/**
 * Set the jitter
 *
 * @api public
 */

Backoff.prototype.setJitter = function(jitter){
  this.jitter = jitter;
};


},{}],8:[function(_dereq_,module,exports){
/**
 * Slice reference.
 */

var slice = [].slice;

/**
 * Bind `obj` to `fn`.
 *
 * @param {Object} obj
 * @param {Function|String} fn or string
 * @return {Function}
 * @api public
 */

module.exports = function(obj, fn){
  if ('string' == typeof fn) fn = obj[fn];
  if ('function' != typeof fn) throw new Error('bind() requires a function');
  var args = slice.call(arguments, 2);
  return function(){
    return fn.apply(obj, args.concat(slice.call(arguments)));
  }
};

},{}],9:[function(_dereq_,module,exports){

/**
 * Expose `Emitter`.
 */

module.exports = Emitter;

/**
 * Initialize a new `Emitter`.
 *
 * @api public
 */

function Emitter(obj) {
  if (obj) return mixin(obj);
};

/**
 * Mixin the emitter properties.
 *
 * @param {Object} obj
 * @return {Object}
 * @api private
 */

function mixin(obj) {
  for (var key in Emitter.prototype) {
    obj[key] = Emitter.prototype[key];
  }
  return obj;
}

/**
 * Listen on the given `event` with `fn`.
 *
 * @param {String} event
 * @param {Function} fn
 * @return {Emitter}
 * @api public
 */

Emitter.prototype.on =
Emitter.prototype.addEventListener = function(event, fn){
  this._callbacks = this._callbacks || {};
  (this._callbacks[event] = this._callbacks[event] || [])
    .push(fn);
  return this;
};

/**
 * Adds an `event` listener that will be invoked a single
 * time then automatically removed.
 *
 * @param {String} event
 * @param {Function} fn
 * @return {Emitter}
 * @api public
 */

Emitter.prototype.once = function(event, fn){
  var self = this;
  this._callbacks = this._callbacks || {};

  function on() {
    self.off(event, on);
    fn.apply(this, arguments);
  }

  on.fn = fn;
  this.on(event, on);
  return this;
};

/**
 * Remove the given callback for `event` or all
 * registered callbacks.
 *
 * @param {String} event
 * @param {Function} fn
 * @return {Emitter}
 * @api public
 */

Emitter.prototype.off =
Emitter.prototype.removeListener =
Emitter.prototype.removeAllListeners =
Emitter.prototype.removeEventListener = function(event, fn){
  this._callbacks = this._callbacks || {};

  // all
  if (0 == arguments.length) {
    this._callbacks = {};
    return this;
  }

  // specific event
  var callbacks = this._callbacks[event];
  if (!callbacks) return this;

  // remove all handlers
  if (1 == arguments.length) {
    delete this._callbacks[event];
    return this;
  }

  // remove specific handler
  var cb;
  for (var i = 0; i < callbacks.length; i++) {
    cb = callbacks[i];
    if (cb === fn || cb.fn === fn) {
      callbacks.splice(i, 1);
      break;
    }
  }
  return this;
};

/**
 * Emit `event` with the given args.
 *
 * @param {String} event
 * @param {Mixed} ...
 * @return {Emitter}
 */

Emitter.prototype.emit = function(event){
  this._callbacks = this._callbacks || {};
  var args = [].slice.call(arguments, 1)
    , callbacks = this._callbacks[event];

  if (callbacks) {
    callbacks = callbacks.slice(0);
    for (var i = 0, len = callbacks.length; i < len; ++i) {
      callbacks[i].apply(this, args);
    }
  }

  return this;
};

/**
 * Return array of callbacks for `event`.
 *
 * @param {String} event
 * @return {Array}
 * @api public
 */

Emitter.prototype.listeners = function(event){
  this._callbacks = this._callbacks || {};
  return this._callbacks[event] || [];
};

/**
 * Check if this emitter has `event` handlers.
 *
 * @param {String} event
 * @return {Boolean}
 * @api public
 */

Emitter.prototype.hasListeners = function(event){
  return !! this.listeners(event).length;
};

},{}],10:[function(_dereq_,module,exports){

/**
 * Expose `debug()` as the module.
 */

module.exports = debug;

/**
 * Create a debugger with the given `name`.
 *
 * @param {String} name
 * @return {Type}
 * @api public
 */

function debug(name) {
  if (!debug.enabled(name)) return function(){};

  return function(fmt){
    fmt = coerce(fmt);

    var curr = new Date;
    var ms = curr - (debug[name] || curr);
    debug[name] = curr;

    fmt = name
      + ' '
      + fmt
      + ' +' + debug.humanize(ms);

    // This hackery is required for IE8
    // where `console.log` doesn't have 'apply'
    window.console
      && console.log
      && Function.prototype.apply.call(console.log, console, arguments);
  }
}

/**
 * The currently active debug mode names.
 */

debug.names = [];
debug.skips = [];

/**
 * Enables a debug mode by name. This can include modes
 * separated by a colon and wildcards.
 *
 * @param {String} name
 * @api public
 */

debug.enable = function(name) {
  try {
    localStorage.debug = name;
  } catch(e){}

  var split = (name || '').split(/[\s,]+/)
    , len = split.length;

  for (var i = 0; i < len; i++) {
    name = split[i].replace('*', '.*?');
    if (name[0] === '-') {
      debug.skips.push(new RegExp('^' + name.substr(1) + '$'));
    }
    else {
      debug.names.push(new RegExp('^' + name + '$'));
    }
  }
};

/**
 * Disable debug output.
 *
 * @api public
 */

debug.disable = function(){
  debug.enable('');
};

/**
 * Humanize the given `ms`.
 *
 * @param {Number} m
 * @return {String}
 * @api private
 */

debug.humanize = function(ms) {
  var sec = 1000
    , min = 60 * 1000
    , hour = 60 * min;

  if (ms >= hour) return (ms / hour).toFixed(1) + 'h';
  if (ms >= min) return (ms / min).toFixed(1) + 'm';
  if (ms >= sec) return (ms / sec | 0) + 's';
  return ms + 'ms';
};

/**
 * Returns true if the given mode name is enabled, false otherwise.
 *
 * @param {String} name
 * @return {Boolean}
 * @api public
 */

debug.enabled = function(name) {
  for (var i = 0, len = debug.skips.length; i < len; i++) {
    if (debug.skips[i].test(name)) {
      return false;
    }
  }
  for (var i = 0, len = debug.names.length; i < len; i++) {
    if (debug.names[i].test(name)) {
      return true;
    }
  }
  return false;
};

/**
 * Coerce `val`.
 */

function coerce(val) {
  if (val instanceof Error) return val.stack || val.message;
  return val;
}

// persist

try {
  if (window.localStorage) debug.enable(localStorage.debug);
} catch(e){}

},{}],11:[function(_dereq_,module,exports){

module.exports =  _dereq_('https://cdn4.tgdd.vn/v2015/Scripts/desktop/V5/lib/');

},{"./lib/":12}],12:[function(_dereq_,module,exports){

module.exports = _dereq_('./socket');

/**
 * Exports parser
 *
 * @api public
 *
 */
module.exports.parser = _dereq_('engine.io-parser');

},{"./socket":13,"engine.io-parser":25}],13:[function(_dereq_,module,exports){
(function (global){
/**
 * Module dependencies.
 */

var transports = _dereq_('./transports');
var Emitter = _dereq_('component-emitter');
var debug = _dereq_('debug')('engine.io-client:socket');
var index = _dereq_('indexof');
var parser = _dereq_('engine.io-parser');
var parseuri = _dereq_('parseuri');
var parsejson = _dereq_('parsejson');
var parseqs = _dereq_('parseqs');

/**
 * Module exports.
 */

module.exports = Socket;

/**
 * Noop function.
 *
 * @api private
 */

function noop(){}

/**
 * Socket constructor.
 *
 * @param {String|Object} uri or options
 * @param {Object} options
 * @api public
 */

function Socket(uri, opts){
  if (!(this instanceof Socket)) return new Socket(uri, opts);

  opts = opts || {};

  if (uri && 'object' == typeof uri) {
    opts = uri;
    uri = null;
  }

  if (uri) {
    uri = parseuri(uri);
    opts.host = uri.host;
    opts.secure = uri.protocol == 'https' || uri.protocol == 'wss';
    opts.port = uri.port;
    if (uri.query) opts.query = uri.query;
  }

  this.secure = null != opts.secure ? opts.secure :
    (global.location && 'https:' == location.protocol);

  if (opts.host) {
    var pieces = opts.host.split(':');
    opts.hostname = pieces.shift();
    if (pieces.length) {
      opts.port = pieces.pop();
    } else if (!opts.port) {
      // if no port is specified manually, use the protocol default
      opts.port = this.secure ? '443' : '80';
    }
  }

  this.agent = opts.agent || false;
  this.hostname = opts.hostname ||
    (global.location ? location.hostname : 'localhost');
  this.port = opts.port || (global.location && location.port ?
       location.port :
       (this.secure ? 443 : 80));
  this.query = opts.query || {};
  if ('string' == typeof this.query) this.query = parseqs.decode(this.query);
  this.upgrade = false !== opts.upgrade;
  this.path = (opts.path || '/engine.io').replace(/\/$/, '') + '/';
  this.forceJSONP = !!opts.forceJSONP;
  this.jsonp = false !== opts.jsonp;
  this.forceBase64 = !!opts.forceBase64;
  this.enablesXDR = !!opts.enablesXDR;
  this.timestampParam = opts.timestampParam || 't';
  this.timestampRequests = opts.timestampRequests;
  this.transports = opts.transports || ['polling', 'websocket'];
  this.readyState = '';
  this.writeBuffer = [];
  this.callbackBuffer = [];
  this.policyPort = opts.policyPort || 843;
  this.rememberUpgrade = opts.rememberUpgrade || false;
  this.binaryType = null;
  this.onlyBinaryUpgrades = opts.onlyBinaryUpgrades;

  // SSL options for Node.js client
  this.pfx = opts.pfx || null;
  this.key = opts.key || null;
  this.passphrase = opts.passphrase || null;
  this.cert = opts.cert || null;
  this.ca = opts.ca || null;
  this.ciphers = opts.ciphers || null;
  this.rejectUnauthorized = opts.rejectUnauthorized || null;

  this.open();
}

Socket.priorWebsocketSuccess = false;

/**
 * Mix in `Emitter`.
 */

Emitter(Socket.prototype);

/**
 * Protocol version.
 *
 * @api public
 */

Socket.protocol = parser.protocol; // this is an int

/**
 * Expose deps for legacy compatibility
 * and standalone browser access.
 */

Socket.Socket = Socket;
Socket.Transport = _dereq_('./transport');
Socket.transports = _dereq_('./transports');
Socket.parser = _dereq_('engine.io-parser');

/**
 * Creates transport of the given type.
 *
 * @param {String} transport name
 * @return {Transport}
 * @api private
 */

Socket.prototype.createTransport = function (name) {
  debug('creating transport "%s"', name);
  var query = clone(this.query);

  // append engine.io protocol identifier
  query.EIO = parser.protocol;

  // transport name
  query.transport = name;

  // session id if we already have one
  if (this.id) query.sid = this.id;

  var transport = new transports[name]({
    agent: this.agent,
    hostname: this.hostname,
    port: this.port,
    secure: this.secure,
    path: this.path,
    query: query,
    forceJSONP: this.forceJSONP,
    jsonp: this.jsonp,
    forceBase64: this.forceBase64,
    enablesXDR: this.enablesXDR,
    timestampRequests: this.timestampRequests,
    timestampParam: this.timestampParam,
    policyPort: this.policyPort,
    socket: this,
    pfx: this.pfx,
    key: this.key,
    passphrase: this.passphrase,
    cert: this.cert,
    ca: this.ca,
    ciphers: this.ciphers,
    rejectUnauthorized: this.rejectUnauthorized
  });

  return transport;
};

function clone (obj) {
  var o = {};
  for (var i in obj) {
    if (obj.hasOwnProperty(i)) {
      o[i] = obj[i];
    }
  }
  return o;
}

/**
 * Initializes transport to use and starts probe.
 *
 * @api private
 */
Socket.prototype.open = function () {
  var transport;
  if (this.rememberUpgrade && Socket.priorWebsocketSuccess && this.transports.indexOf('websocket') != -1) {
    transport = 'websocket';
  } else if (0 == this.transports.length) {
    // Emit error on next tick so it can be listened to
    var self = this;
    setTimeout(function() {
      self.emit('error', 'No transports available');
    }, 0);
    return;
  } else {
    transport = this.transports[0];
  }
  this.readyState = 'opening';

  // Retry with the next transport if the transport is disabled (jsonp: false)
  var transport;
  try {
    transport = this.createTransport(transport);
  } catch (e) {
    this.transports.shift();
    this.open();
    return;
  }

  transport.open();
  this.setTransport(transport);
};

/**
 * Sets the current transport. Disables the existing one (if any).
 *
 * @api private
 */

Socket.prototype.setTransport = function(transport){
  debug('setting transport %s', transport.name);
  var self = this;

  if (this.transport) {
    debug('clearing existing transport %s', this.transport.name);
    this.transport.removeAllListeners();
  }

  // set up transport
  this.transport = transport;

  // set up transport listeners
  transport
  .on('drain', function(){
    self.onDrain();
  })
  .on('packet', function(packet){
    self.onPacket(packet);
  })
  .on('error', function(e){
    self.onError(e);
  })
  .on('close', function(){
    self.onClose('transport close');
  });
};

/**
 * Probes a transport.
 *
 * @param {String} transport name
 * @api private
 */

Socket.prototype.probe = function (name) {
  debug('probing transport "%s"', name);
  var transport = this.createTransport(name, { probe: 1 })
    , failed = false
    , self = this;

  Socket.priorWebsocketSuccess = false;

  function onTransportOpen(){
    if (self.onlyBinaryUpgrades) {
      var upgradeLosesBinary = !this.supportsBinary && self.transport.supportsBinary;
      failed = failed || upgradeLosesBinary;
    }
    if (failed) return;

    debug('probe transport "%s" opened', name);
    transport.send([{ type: 'ping', data: 'probe' }]);
    transport.once('packet', function (msg) {
      if (failed) return;
      if ('pong' == msg.type && 'probe' == msg.data) {
        debug('probe transport "%s" pong', name);
        self.upgrading = true;
        self.emit('upgrading', transport);
        if (!transport) return;
        Socket.priorWebsocketSuccess = 'websocket' == transport.name;

        debug('pausing current transport "%s"', self.transport.name);
        self.transport.pause(function () {
          if (failed) return;
          if ('closed' == self.readyState) return;
          debug('changing transport and sending upgrade packet');

          cleanup();

          self.setTransport(transport);
          transport.send([{ type: 'upgrade' }]);
          self.emit('upgrade', transport);
          transport = null;
          self.upgrading = false;
          self.flush();
        });
      } else {
        debug('probe transport "%s" failed', name);
        var err = new Error('probe error');
        err.transport = transport.name;
        self.emit('upgradeError', err);
      }
    });
  }

  function freezeTransport() {
    if (failed) return;

    // Any callback called by transport should be ignored since now
    failed = true;

    cleanup();

    transport.close();
    transport = null;
  }

  //Handle any error that happens while probing
  function onerror(err) {
    var error = new Error('probe error: ' + err);
    error.transport = transport.name;

    freezeTransport();

    debug('probe transport "%s" failed because of error: %s', name, err);

    self.emit('upgradeError', error);
  }

  function onTransportClose(){
    onerror("transport closed");
  }

  //When the socket is closed while we're probing
  function onclose(){
    onerror("socket closed");
  }

  //When the socket is upgraded while we're probing
  function onupgrade(to){
    if (transport && to.name != transport.name) {
      debug('"%s" works - aborting "%s"', to.name, transport.name);
      freezeTransport();
    }
  }

  //Remove all listeners on the transport and on self
  function cleanup(){
    transport.removeListener('open', onTransportOpen);
    transport.removeListener('error', onerror);
    transport.removeListener('close', onTransportClose);
    self.removeListener('close', onclose);
    self.removeListener('upgrading', onupgrade);
  }

  transport.once('open', onTransportOpen);
  transport.once('error', onerror);
  transport.once('close', onTransportClose);

  this.once('close', onclose);
  this.once('upgrading', onupgrade);

  transport.open();

};

/**
 * Called when connection is deemed open.
 *
 * @api public
 */

Socket.prototype.onOpen = function () {
  debug('socket open');
  this.readyState = 'open';
  Socket.priorWebsocketSuccess = 'websocket' == this.transport.name;
  this.emit('open');
  this.flush();

  // we check for `readyState` in case an `open`
  // listener already closed the socket
  if ('open' == this.readyState && this.upgrade && this.transport.pause) {
    debug('starting upgrade probes');
    for (var i = 0, l = this.upgrades.length; i < l; i++) {
      this.probe(this.upgrades[i]);
    }
  }
};

/**
 * Handles a packet.
 *
 * @api private
 */

Socket.prototype.onPacket = function (packet) {
  if ('opening' == this.readyState || 'open' == this.readyState) {
    debug('socket receive: type "%s", data "%s"', packet.type, packet.data);

    this.emit('packet', packet);

    // Socket is live - any packet counts
    this.emit('heartbeat');

    switch (packet.type) {
      case 'open':
        this.onHandshake(parsejson(packet.data));
        break;

      case 'pong':
        this.setPing();
        break;

      case 'error':
        var err = new Error('server error');
        err.code = packet.data;
        this.emit('error', err);
        break;

      case 'message':
        this.emit('data', packet.data);
        this.emit('message', packet.data);
        break;
    }
  } else {
    debug('packet received with socket readyState "%s"', this.readyState);
  }
};

/**
 * Called upon handshake completion.
 *
 * @param {Object} handshake obj
 * @api private
 */

Socket.prototype.onHandshake = function (data) {
  this.emit('handshake', data);
  this.id = data.sid;
  this.transport.query.sid = data.sid;
  this.upgrades = this.filterUpgrades(data.upgrades);
  this.pingInterval = data.pingInterval;
  this.pingTimeout = data.pingTimeout;
  this.onOpen();
  // In case open handler closes socket
  if  ('closed' == this.readyState) return;
  this.setPing();

  // Prolong liveness of socket on heartbeat
  this.removeListener('heartbeat', this.onHeartbeat);
  this.on('heartbeat', this.onHeartbeat);
};

/**
 * Resets ping timeout.
 *
 * @api private
 */

Socket.prototype.onHeartbeat = function (timeout) {
  clearTimeout(this.pingTimeoutTimer);
  var self = this;
  self.pingTimeoutTimer = setTimeout(function () {
    if ('closed' == self.readyState) return;
    self.onClose('ping timeout');
  }, timeout || (self.pingInterval + self.pingTimeout));
};

/**
 * Pings server every `this.pingInterval` and expects response
 * within `this.pingTimeout` or closes connection.
 *
 * @api private
 */

Socket.prototype.setPing = function () {
  var self = this;
  clearTimeout(self.pingIntervalTimer);
  self.pingIntervalTimer = setTimeout(function () {
    debug('writing ping packet - expecting pong within %sms', self.pingTimeout);
    self.ping();
    self.onHeartbeat(self.pingTimeout);
  }, self.pingInterval);
};

/**
* Sends a ping packet.
*
* @api public
*/

Socket.prototype.ping = function () {
  this.sendPacket('ping');
};

/**
 * Called on `drain` event
 *
 * @api private
 */

Socket.prototype.onDrain = function() {
  for (var i = 0; i < this.prevBufferLen; i++) {
    if (this.callbackBuffer[i]) {
      this.callbackBuffer[i]();
    }
  }

  this.writeBuffer.splice(0, this.prevBufferLen);
  this.callbackBuffer.splice(0, this.prevBufferLen);

  // setting prevBufferLen = 0 is very important
  // for example, when upgrading, upgrade packet is sent over,
  // and a nonzero prevBufferLen could cause problems on `drain`
  this.prevBufferLen = 0;

  if (this.writeBuffer.length == 0) {
    this.emit('drain');
  } else {
    this.flush();
  }
};

/**
 * Flush write buffers.
 *
 * @api private
 */

Socket.prototype.flush = function () {
  if ('closed' != this.readyState && this.transport.writable &&
    !this.upgrading && this.writeBuffer.length) {
    debug('flushing %d packets in socket', this.writeBuffer.length);
    this.transport.send(this.writeBuffer);
    // keep track of current length of writeBuffer
    // splice writeBuffer and callbackBuffer on `drain`
    this.prevBufferLen = this.writeBuffer.length;
    this.emit('flush');
  }
};

/**
 * Sends a message.
 *
 * @param {String} message.
 * @param {Function} callback function.
 * @return {Socket} for chaining.
 * @api public
 */

Socket.prototype.write =
Socket.prototype.send = function (msg, fn) {
  this.sendPacket('message', msg, fn);
  return this;
};

/**
 * Sends a packet.
 *
 * @param {String} packet type.
 * @param {String} data.
 * @param {Function} callback function.
 * @api private
 */

Socket.prototype.sendPacket = function (type, data, fn) {
  if ('closing' == this.readyState || 'closed' == this.readyState) {
    return;
  }

  var packet = { type: type, data: data };
  this.emit('packetCreate', packet);
  this.writeBuffer.push(packet);
  this.callbackBuffer.push(fn);
  this.flush();
};

/**
 * Closes the connection.
 *
 * @api private
 */

Socket.prototype.close = function () {
  if ('opening' == this.readyState || 'open' == this.readyState) {
    this.readyState = 'closing';

    var self = this;

    function close() {
      self.onClose('forced close');
      debug('socket closing - telling transport to close');
      self.transport.close();
    }

    function cleanupAndClose() {
      self.removeListener('upgrade', cleanupAndClose);
      self.removeListener('upgradeError', cleanupAndClose);
      close();
    }

    function waitForUpgrade() {
      // wait for upgrade to finish since we can't send packets while pausing a transport
      self.once('upgrade', cleanupAndClose);
      self.once('upgradeError', cleanupAndClose);
    }

    if (this.writeBuffer.length) {
      this.once('drain', function() {
        if (this.upgrading) {
          waitForUpgrade();
        } else {
          close();
        }
      });
    } else if (this.upgrading) {
      waitForUpgrade();
    } else {
      close();
    }
  }

  return this;
};

/**
 * Called upon transport error
 *
 * @api private
 */

Socket.prototype.onError = function (err) {
  debug('socket error %j', err);
  Socket.priorWebsocketSuccess = false;
  this.emit('error', err);
  this.onClose('transport error', err);
};

/**
 * Called upon transport close.
 *
 * @api private
 */

Socket.prototype.onClose = function (reason, desc) {
  if ('opening' == this.readyState || 'open' == this.readyState || 'closing' == this.readyState) {
    debug('socket close with reason: "%s"', reason);
    var self = this;

    // clear timers
    clearTimeout(this.pingIntervalTimer);
    clearTimeout(this.pingTimeoutTimer);

    // clean buffers in next tick, so developers can still
    // grab the buffers on `close` event
    setTimeout(function() {
      self.writeBuffer = [];
      self.callbackBuffer = [];
      self.prevBufferLen = 0;
    }, 0);

    // stop event from firing again for transport
    this.transport.removeAllListeners('close');

    // ensure transport won't stay open
    this.transport.close();

    // ignore further transport communication
    this.transport.removeAllListeners();

    // set ready state
    this.readyState = 'closed';

    // clear session id
    this.id = null;

    // emit close event
    this.emit('close', reason, desc);
  }
};

/**
 * Filters upgrades, returning only those matching client transports.
 *
 * @param {Array} server upgrades
 * @api private
 *
 */

Socket.prototype.filterUpgrades = function (upgrades) {
  var filteredUpgrades = [];
  for (var i = 0, j = upgrades.length; i<j; i++) {
    if (~index(this.transports, upgrades[i])) filteredUpgrades.push(upgrades[i]);
  }
  return filteredUpgrades;
};

}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"./transport":14,"./transports":15,"component-emitter":9,"debug":22,"engine.io-parser":25,"indexof":42,"parsejson":34,"parseqs":35,"parseuri":36}],14:[function(_dereq_,module,exports){
/**
 * Module dependencies.
 */

var parser = _dereq_('engine.io-parser');
var Emitter = _dereq_('component-emitter');

/**
 * Module exports.
 */

module.exports = Transport;

/**
 * Transport abstract constructor.
 *
 * @param {Object} options.
 * @api private
 */

function Transport (opts) {
  this.path = opts.path;
  this.hostname = opts.hostname;
  this.port = opts.port;
  this.secure = opts.secure;
  this.query = opts.query;
  this.timestampParam = opts.timestampParam;
  this.timestampRequests = opts.timestampRequests;
  this.readyState = '';
  this.agent = opts.agent || false;
  this.socket = opts.socket;
  this.enablesXDR = opts.enablesXDR;

  // SSL options for Node.js client
  this.pfx = opts.pfx;
  this.key = opts.key;
  this.passphrase = opts.passphrase;
  this.cert = opts.cert;
  this.ca = opts.ca;
  this.ciphers = opts.ciphers;
  this.rejectUnauthorized = opts.rejectUnauthorized;
}

/**
 * Mix in `Emitter`.
 */

Emitter(Transport.prototype);

/**
 * A counter used to prevent collisions in the timestamps used
 * for cache busting.
 */

Transport.timestamps = 0;

/**
 * Emits an error.
 *
 * @param {String} str
 * @return {Transport} for chaining
 * @api public
 */

Transport.prototype.onError = function (msg, desc) {
  var err = new Error(msg);
  err.type = 'TransportError';
  err.description = desc;
  this.emit('error', err);
  return this;
};

/**
 * Opens the transport.
 *
 * @api public
 */

Transport.prototype.open = function () {
  if ('closed' == this.readyState || '' == this.readyState) {
    this.readyState = 'opening';
    this.doOpen();
  }

  return this;
};

/**
 * Closes the transport.
 *
 * @api private
 */

Transport.prototype.close = function () {
  if ('opening' == this.readyState || 'open' == this.readyState) {
    this.doClose();
    this.onClose();
  }

  return this;
};

/**
 * Sends multiple packets.
 *
 * @param {Array} packets
 * @api private
 */

Transport.prototype.send = function(packets){
  if ('open' == this.readyState) {
    this.write(packets);
  } else {
    throw new Error('Transport not open');
  }
};

/**
 * Called upon open
 *
 * @api private
 */

Transport.prototype.onOpen = function () {
  this.readyState = 'open';
  this.writable = true;
  this.emit('open');
};

/**
 * Called with data.
 *
 * @param {String} data
 * @api private
 */

Transport.prototype.onData = function(data){
  var packet = parser.decodePacket(data, this.socket.binaryType);
  this.onPacket(packet);
};

/**
 * Called with a decoded packet.
 */

Transport.prototype.onPacket = function (packet) {
  this.emit('packet', packet);
};

/**
 * Called upon close.
 *
 * @api private
 */

Transport.prototype.onClose = function () {
  this.readyState = 'closed';
  this.emit('close');
};

},{"component-emitter":9,"engine.io-parser":25}],15:[function(_dereq_,module,exports){
(function (global){
/**
 * Module dependencies
 */

var XMLHttpRequest = _dereq_('xmlhttprequest');
var XHR = _dereq_('./polling-xhr');
var JSONP = _dereq_('./polling-jsonp');
var websocket = _dereq_('./websocket');

/**
 * Export transports.
 */

exports.polling = polling;
exports.websocket = websocket;

/**
 * Polling transport polymorphic constructor.
 * Decides on xhr vs jsonp based on feature detection.
 *
 * @api private
 */

function polling(opts){
  var xhr;
  var xd = false;
  var xs = false;
  var jsonp = false !== opts.jsonp;

  if (global.location) {
    var isSSL = 'https:' == location.protocol;
    var port = location.port;

    // some user agents have empty `location.port`
    if (!port) {
      port = isSSL ? 443 : 80;
    }

    xd = opts.hostname != location.hostname || port != opts.port;
    xs = opts.secure != isSSL;
  }

  opts.xdomain = xd;
  opts.xscheme = xs;
  xhr = new XMLHttpRequest(opts);

  if ('open' in xhr && !opts.forceJSONP) {
    return new XHR(opts);
  } else {
    if (!jsonp) throw new Error('JSONP disabled');
    return new JSONP(opts);
  }
}

}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"./polling-jsonp":16,"./polling-xhr":17,"./websocket":19,"xmlhttprequest":20}],16:[function(_dereq_,module,exports){
(function (global){

/**
 * Module requirements.
 */

var Polling = _dereq_('./polling');
var inherit = _dereq_('component-inherit');

/**
 * Module exports.
 */

module.exports = JSONPPolling;

/**
 * Cached regular expressions.
 */

var rNewline = /\n/g;
var rEscapedNewline = /\\n/g;

/**
 * Global JSONP callbacks.
 */

var callbacks;

/**
 * Callbacks count.
 */

var index = 0;

/**
 * Noop.
 */

function empty () { }

/**
 * JSONP Polling constructor.
 *
 * @param {Object} opts.
 * @api public
 */

function JSONPPolling (opts) {
  Polling.call(this, opts);

  this.query = this.query || {};

  // define global callbacks array if not present
  // we do this here (lazily) to avoid unneeded global pollution
  if (!callbacks) {
    // we need to consider multiple engines in the same page
    if (!global.___eio) global.___eio = [];
    callbacks = global.___eio;
  }

  // callback identifier
  this.index = callbacks.length;

  // add callback to jsonp global
  var self = this;
  callbacks.push(function (msg) {
    self.onData(msg);
  });

  // append to query string
  this.query.j = this.index;

  // prevent spurious errors from being emitted when the window is unloaded
  if (global.document && global.addEventListener) {
    global.addEventListener('beforeunload', function () {
      if (self.script) self.script.onerror = empty;
    }, false);
  }
}

/**
 * Inherits from Polling.
 */

inherit(JSONPPolling, Polling);

/*
 * JSONP only supports binary as base64 encoded strings
 */

JSONPPolling.prototype.supportsBinary = false;

/**
 * Closes the socket.
 *
 * @api private
 */

JSONPPolling.prototype.doClose = function () {
  if (this.script) {
    this.script.parentNode.removeChild(this.script);
    this.script = null;
  }

  if (this.form) {
    this.form.parentNode.removeChild(this.form);
    this.form = null;
    this.iframe = null;
  }

  Polling.prototype.doClose.call(this);
};

/**
 * Starts a poll cycle.
 *
 * @api private
 */

JSONPPolling.prototype.doPoll = function () {
  var self = this;
  var script = document.createElement('script');

  if (this.script) {
    this.script.parentNode.removeChild(this.script);
    this.script = null;
  }

  script.async = true;
  script.src = this.uri();
  script.onerror = function(e){
    self.onError('jsonp poll error',e);
  };

  var insertAt = document.getElementsByTagName('script')[0];
  insertAt.parentNode.insertBefore(script, insertAt);
  this.script = script;

  var isUAgecko = 'undefined' != typeof navigator && /gecko/i.test(navigator.userAgent);
  
  if (isUAgecko) {
    setTimeout(function () {
      var iframe = document.createElement('iframe');
      document.body.appendChild(iframe);
      document.body.removeChild(iframe);
    }, 100);
  }
};

/**
 * Writes with a hidden iframe.
 *
 * @param {String} data to send
 * @param {Function} called upon flush.
 * @api private
 */

JSONPPolling.prototype.doWrite = function (data, fn) {
  var self = this;

  if (!this.form) {
    var form = document.createElement('form');
    var area = document.createElement('textarea');
    var id = this.iframeId = 'eio_iframe_' + this.index;
    var iframe;

    form.className = 'socketio';
    form.style.position = 'absolute';
    form.style.top = '-1000px';
    form.style.left = '-1000px';
    form.target = id;
    form.method = 'POST';
    form.setAttribute('accept-charset', 'utf-8');
    area.name = 'd';
    form.appendChild(area);
    document.body.appendChild(form);

    this.form = form;
    this.area = area;
  }

  this.form.action = this.uri();

  function complete () {
    initIframe();
    fn();
  }

  function initIframe () {
    if (self.iframe) {
      try {
        self.form.removeChild(self.iframe);
      } catch (e) {
        self.onError('jsonp polling iframe removal error', e);
      }
    }

    try {
      // ie6 dynamic iframes with target="" support (thanks Chris Lambacher)
      var html = '<iframe src="javascript:0" name="'+ self.iframeId +'">';
      iframe = document.createElement(html);
    } catch (e) {
      iframe = document.createElement('iframe');
      iframe.name = self.iframeId;
      iframe.src = 'javascript:0';
    }

    iframe.id = self.iframeId;

    self.form.appendChild(iframe);
    self.iframe = iframe;
  }

  initIframe();

  // escape \n to prevent it from being converted into \r\n by some UAs
  // double escaping is required for escaped new lines because unescaping of new lines can be done safely on server-side
  data = data.replace(rEscapedNewline, '\\\n');
  this.area.value = data.replace(rNewline, '\\n');

  try {
    this.form.submit();
  } catch(e) {}

  if (this.iframe.attachEvent) {
    this.iframe.onreadystatechange = function(){
      if (self.iframe.readyState == 'complete') {
        complete();
      }
    };
  } else {
    this.iframe.onload = complete;
  }
};

}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"./polling":18,"component-inherit":21}],17:[function(_dereq_,module,exports){
(function (global){
/**
 * Module requirements.
 */

var XMLHttpRequest = _dereq_('xmlhttprequest');
var Polling = _dereq_('./polling');
var Emitter = _dereq_('component-emitter');
var inherit = _dereq_('component-inherit');
var debug = _dereq_('debug')('engine.io-client:polling-xhr');

/**
 * Module exports.
 */

module.exports = XHR;
module.exports.Request = Request;

/**
 * Empty function
 */

function empty(){}

/**
 * XHR Polling constructor.
 *
 * @param {Object} opts
 * @api public
 */

function XHR(opts){
  Polling.call(this, opts);

  if (global.location) {
    var isSSL = 'https:' == location.protocol;
    var port = location.port;

    // some user agents have empty `location.port`
    if (!port) {
      port = isSSL ? 443 : 80;
    }

    this.xd = opts.hostname != global.location.hostname ||
      port != opts.port;
    this.xs = opts.secure != isSSL;
  }
}

/**
 * Inherits from Polling.
 */

inherit(XHR, Polling);

/**
 * XHR supports binary
 */

XHR.prototype.supportsBinary = true;

/**
 * Creates a request.
 *
 * @param {String} method
 * @api private
 */

XHR.prototype.request = function(opts){
  opts = opts || {};
  opts.uri = this.uri();
  opts.xd = this.xd;
  opts.xs = this.xs;
  opts.agent = this.agent || false;
  opts.supportsBinary = this.supportsBinary;
  opts.enablesXDR = this.enablesXDR;

  // SSL options for Node.js client
  opts.pfx = this.pfx;
  opts.key = this.key;
  opts.passphrase = this.passphrase;
  opts.cert = this.cert;
  opts.ca = this.ca;
  opts.ciphers = this.ciphers;
  opts.rejectUnauthorized = this.rejectUnauthorized;

  return new Request(opts);
};

/**
 * Sends data.
 *
 * @param {String} data to send.
 * @param {Function} called upon flush.
 * @api private
 */

XHR.prototype.doWrite = function(data, fn){
  var isBinary = typeof data !== 'string' && data !== undefined;
  var req = this.request({ method: 'POST', data: data, isBinary: isBinary });
  var self = this;
  req.on('success', fn);
  req.on('error', function(err){
    self.onError('xhr post error', err);
  });
  this.sendXhr = req;
};

/**
 * Starts a poll cycle.
 *
 * @api private
 */

XHR.prototype.doPoll = function(){
  debug('xhr poll');
  var req = this.request();
  var self = this;
  req.on('data', function(data){
    self.onData(data);
  });
  req.on('error', function(err){
    self.onError('xhr poll error', err);
  });
  this.pollXhr = req;
};

/**
 * Request constructor
 *
 * @param {Object} options
 * @api public
 */

function Request(opts){
  this.method = opts.method || 'GET';
  this.uri = opts.uri;
  this.xd = !!opts.xd;
  this.xs = !!opts.xs;
  this.async = false !== opts.async;
  this.data = undefined != opts.data ? opts.data : null;
  this.agent = opts.agent;
  this.isBinary = opts.isBinary;
  this.supportsBinary = opts.supportsBinary;
  this.enablesXDR = opts.enablesXDR;

  // SSL options for Node.js client
  this.pfx = opts.pfx;
  this.key = opts.key;
  this.passphrase = opts.passphrase;
  this.cert = opts.cert;
  this.ca = opts.ca;
  this.ciphers = opts.ciphers;
  this.rejectUnauthorized = opts.rejectUnauthorized;

  this.create();
}

/**
 * Mix in `Emitter`.
 */

Emitter(Request.prototype);

/**
 * Creates the XHR object and sends the request.
 *
 * @api private
 */

Request.prototype.create = function(){
  var opts = { agent: this.agent, xdomain: this.xd, xscheme: this.xs, enablesXDR: this.enablesXDR };

  // SSL options for Node.js client
  opts.pfx = this.pfx;
  opts.key = this.key;
  opts.passphrase = this.passphrase;
  opts.cert = this.cert;
  opts.ca = this.ca;
  opts.ciphers = this.ciphers;
  opts.rejectUnauthorized = this.rejectUnauthorized;

  var xhr = this.xhr = new XMLHttpRequest(opts);
  var self = this;

  try {
    debug('xhr open %s: %s', this.method, this.uri);
    xhr.open(this.method, this.uri, this.async);
    if (this.supportsBinary) {
      // This has to be done after open because Firefox is stupid
      // http://stackoverflow.com/questions/13216903/get-binary-data-with-xmlhttprequest-in-a-firefox-extension
      xhr.responseType = 'arraybuffer';
    }

    if ('POST' == this.method) {
      try {
        if (this.isBinary) {
          xhr.setRequestHeader('Content-type', 'application/octet-stream');
        } else {
          xhr.setRequestHeader('Content-type', 'text/plain;charset=UTF-8');
        }
      } catch (e) {}
    }

    // ie6 check
    if ('withCredentials' in xhr) {
      xhr.withCredentials = true;
    }

    if (this.hasXDR()) {
      xhr.onload = function(){
        self.onLoad();
      };
      xhr.onerror = function(){
        self.onError(xhr.responseText);
      };
    } else {
      xhr.onreadystatechange = function(){
        if (4 != xhr.readyState) return;
        if (200 == xhr.status || 1223 == xhr.status) {
          self.onLoad();
        } else {
          // make sure the `error` event handler that's user-set
          // does not throw in the same tick and gets caught here
          setTimeout(function(){
            self.onError(xhr.status);
          }, 0);
        }
      };
    }

    debug('xhr data %s', this.data);
    xhr.send(this.data);
  } catch (e) {
    // Need to defer since .create() is called directly fhrom the constructor
    // and thus the 'error' event can only be only bound *after* this exception
    // occurs.  Therefore, also, we cannot throw here at all.
    setTimeout(function() {
      self.onError(e);
    }, 0);
    return;
  }

  if (global.document) {
    this.index = Request.requestsCount++;
    Request.requests[this.index] = this;
  }
};

/**
 * Called upon successful response.
 *
 * @api private
 */

Request.prototype.onSuccess = function(){
  this.emit('success');
  this.cleanup();
};

/**
 * Called if we have data.
 *
 * @api private
 */

Request.prototype.onData = function(data){
  this.emit('data', data);
  this.onSuccess();
};

/**
 * Called upon error.
 *
 * @api private
 */

Request.prototype.onError = function(err){
  this.emit('error', err);
  this.cleanup(true);
};

/**
 * Cleans up house.
 *
 * @api private
 */

Request.prototype.cleanup = function(fromError){
  if ('undefined' == typeof this.xhr || null === this.xhr) {
    return;
  }
  // xmlhttprequest
  if (this.hasXDR()) {
    this.xhr.onload = this.xhr.onerror = empty;
  } else {
    this.xhr.onreadystatechange = empty;
  }

  if (fromError) {
    try {
      this.xhr.abort();
    } catch(e) {}
  }

  if (global.document) {
    delete Request.requests[this.index];
  }

  this.xhr = null;
};

/**
 * Called upon load.
 *
 * @api private
 */

Request.prototype.onLoad = function(){
  var data;
  try {
    var contentType;
    try {
      contentType = this.xhr.getResponseHeader('Content-Type').split(';')[0];
    } catch (e) {}
    if (contentType === 'application/octet-stream') {
      data = this.xhr.response;
    } else {
      if (!this.supportsBinary) {
        data = this.xhr.responseText;
      } else {
        data = 'ok';
      }
    }
  } catch (e) {
    this.onError(e);
  }
  if (null != data) {
    this.onData(data);
  }
};

/**
 * Check if it has XDomainRequest.
 *
 * @api private
 */

Request.prototype.hasXDR = function(){
  return 'undefined' !== typeof global.XDomainRequest && !this.xs && this.enablesXDR;
};

/**
 * Aborts the request.
 *
 * @api public
 */

Request.prototype.abort = function(){
  this.cleanup();
};

/**
 * Aborts pending requests when unloading the window. This is needed to prevent
 * memory leaks (e.g. when using IE) and to ensure that no spurious error is
 * emitted.
 */

if (global.document) {
  Request.requestsCount = 0;
  Request.requests = {};
  if (global.attachEvent) {
    global.attachEvent('onunload', unloadHandler);
  } else if (global.addEventListener) {
    global.addEventListener('beforeunload', unloadHandler, false);
  }
}

function unloadHandler() {
  for (var i in Request.requests) {
    if (Request.requests.hasOwnProperty(i)) {
      Request.requests[i].abort();
    }
  }
}

}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"./polling":18,"component-emitter":9,"component-inherit":21,"debug":22,"xmlhttprequest":20}],18:[function(_dereq_,module,exports){
/**
 * Module dependencies.
 */

var Transport = _dereq_('../transport');
var parseqs = _dereq_('parseqs');
var parser = _dereq_('engine.io-parser');
var inherit = _dereq_('component-inherit');
var debug = _dereq_('debug')('engine.io-client:polling');

/**
 * Module exports.
 */

module.exports = Polling;

/**
 * Is XHR2 supported?
 */

var hasXHR2 = (function() {
  var XMLHttpRequest = _dereq_('xmlhttprequest');
  var xhr = new XMLHttpRequest({ xdomain: false });
  return null != xhr.responseType;
})();

/**
 * Polling interface.
 *
 * @param {Object} opts
 * @api private
 */

function Polling(opts){
  var forceBase64 = (opts && opts.forceBase64);
  if (!hasXHR2 || forceBase64) {
    this.supportsBinary = false;
  }
  Transport.call(this, opts);
}

/**
 * Inherits from Transport.
 */

inherit(Polling, Transport);

/**
 * Transport name.
 */

Polling.prototype.name = 'polling';

/**
 * Opens the socket (triggers polling). We write a PING message to determine
 * when the transport is open.
 *
 * @api private
 */

Polling.prototype.doOpen = function(){
  this.poll();
};

/**
 * Pauses polling.
 *
 * @param {Function} callback upon buffers are flushed and transport is paused
 * @api private
 */

Polling.prototype.pause = function(onPause){
  var pending = 0;
  var self = this;

  this.readyState = 'pausing';

  function pause(){
    debug('paused');
    self.readyState = 'paused';
    onPause();
  }

  if (this.polling || !this.writable) {
    var total = 0;

    if (this.polling) {
      debug('we are currently polling - waiting to pause');
      total++;
      this.once('pollComplete', function(){
        debug('pre-pause polling complete');
        --total || pause();
      });
    }

    if (!this.writable) {
      debug('we are currently writing - waiting to pause');
      total++;
      this.once('drain', function(){
        debug('pre-pause writing complete');
        --total || pause();
      });
    }
  } else {
    pause();
  }
};

/**
 * Starts polling cycle.
 *
 * @api public
 */

Polling.prototype.poll = function(){
  debug('polling');
  this.polling = true;
  this.doPoll();
  this.emit('poll');
};

/**
 * Overloads onData to detect payloads.
 *
 * @api private
 */

Polling.prototype.onData = function(data){
  var self = this;
  debug('polling got data %s', data);
  var callback = function(packet, index, total) {
    // if its the first message we consider the transport open
    if ('opening' == self.readyState) {
      self.onOpen();
    }

    // if its a close packet, we close the ongoing requests
    if ('close' == packet.type) {
      self.onClose();
      return false;
    }

    // otherwise bypass onData and handle the message
    self.onPacket(packet);
  };

  // decode payload
  parser.decodePayload(data, this.socket.binaryType, callback);

  // if an event did not trigger closing
  if ('closed' != this.readyState) {
    // if we got data we're not polling
    this.polling = false;
    this.emit('pollComplete');

    if ('open' == this.readyState) {
      this.poll();
    } else {
      debug('ignoring poll - transport state "%s"', this.readyState);
    }
  }
};

/**
 * For polling, send a close packet.
 *
 * @api private
 */

Polling.prototype.doClose = function(){
  var self = this;

  function close(){
    debug('writing close packet');
    self.write([{ type: 'close' }]);
  }

  if ('open' == this.readyState) {
    debug('transport open - closing');
    close();
  } else {
    // in case we're trying to close while
    // handshaking is in progress (GH-164)
    debug('transport not open - deferring close');
    this.once('open', close);
  }
};

/**
 * Writes a packets payload.
 *
 * @param {Array} data packets
 * @param {Function} drain callback
 * @api private
 */

Polling.prototype.write = function(packets){
  var self = this;
  this.writable = false;
  var callbackfn = function() {
    self.writable = true;
    self.emit('drain');
  };

  var self = this;
  parser.encodePayload(packets, this.supportsBinary, function(data) {
    self.doWrite(data, callbackfn);
  });
};

/**
 * Generates uri for connection.
 *
 * @api private
 */

Polling.prototype.uri = function(){
  var query = this.query || {};
  var schema = this.secure ? 'https' : 'http';
  var port = '';

  // cache busting is forced
  if (false !== this.timestampRequests) {
    query[this.timestampParam] = +new Date + '-' + Transport.timestamps++;
  }

  if (!this.supportsBinary && !query.sid) {
    query.b64 = 1;
  }

  query = parseqs.encode(query);

  // avoid port if default for schema
  if (this.port && (('https' == schema && this.port != 443) ||
     ('http' == schema && this.port != 80))) {
    port = ':' + this.port;
  }

  // prepend ? to query
  if (query.length) {
    query = '?' + query;
  }

  return schema + '://' + this.hostname + port + this.path + query;
};

},{"../transport":14,"component-inherit":21,"debug":22,"engine.io-parser":25,"parseqs":35,"xmlhttprequest":20}],19:[function(_dereq_,module,exports){
/**
 * Module dependencies.
 */

var Transport = _dereq_('../transport');
var parser = _dereq_('engine.io-parser');
var parseqs = _dereq_('parseqs');
var inherit = _dereq_('component-inherit');
var debug = _dereq_('debug')('engine.io-client:websocket');

/**
 * `ws` exposes a WebSocket-compatible interface in
 * Node, or the `WebSocket` or `MozWebSocket` globals
 * in the browser.
 */

var WebSocket = _dereq_('ws');

/**
 * Module exports.
 */

module.exports = WS;

/**
 * WebSocket transport constructor.
 *
 * @api {Object} connection options
 * @api public
 */

function WS(opts){
  var forceBase64 = (opts && opts.forceBase64);
  if (forceBase64) {
    this.supportsBinary = false;
  }
  Transport.call(this, opts);
}

/**
 * Inherits from Transport.
 */

inherit(WS, Transport);

/**
 * Transport name.
 *
 * @api public
 */

WS.prototype.name = 'websocket';

/*
 * WebSockets support binary
 */

WS.prototype.supportsBinary = true;

/**
 * Opens socket.
 *
 * @api private
 */

WS.prototype.doOpen = function(){
  if (!this.check()) {
    // let probe timeout
    return;
  }

  var self = this;
  var uri = this.uri();
  var protocols = void(0);
  var opts = { agent: this.agent };

  // SSL options for Node.js client
  opts.pfx = this.pfx;
  opts.key = this.key;
  opts.passphrase = this.passphrase;
  opts.cert = this.cert;
  opts.ca = this.ca;
  opts.ciphers = this.ciphers;
  opts.rejectUnauthorized = this.rejectUnauthorized;

  this.ws = new WebSocket(uri, protocols, opts);

  if (this.ws.binaryType === undefined) {
    this.supportsBinary = false;
  }

  this.ws.binaryType = 'arraybuffer';
  this.addEventListeners();
};

/**
 * Adds event listeners to the socket
 *
 * @api private
 */

WS.prototype.addEventListeners = function(){
  var self = this;

  this.ws.onopen = function(){
    self.onOpen();
  };
  this.ws.onclose = function(){
    self.onClose();
  };
  this.ws.onmessage = function(ev){
    self.onData(ev.data);
  };
  this.ws.onerror = function(e){
    self.onError('websocket error', e);
  };
};

/**
 * Override `onData` to use a timer on iOS.
 * See: https://gist.github.com/mloughran/2052006
 *
 * @api private
 */

if ('undefined' != typeof navigator
  && /iPad|iPhone|iPod/i.test(navigator.userAgent)) {
  WS.prototype.onData = function(data){
    var self = this;
    setTimeout(function(){
      Transport.prototype.onData.call(self, data);
    }, 0);
  };
}

/**
 * Writes data to socket.
 *
 * @param {Array} array of packets.
 * @api private
 */

WS.prototype.write = function(packets){
  var self = this;
  this.writable = false;
  // encodePacket efficient as it uses WS framing
  // no need for encodePayload
  for (var i = 0, l = packets.length; i < l; i++) {
    parser.encodePacket(packets[i], this.supportsBinary, function(data) {
      //Sometimes the websocket has already been closed but the browser didn't
      //have a chance of informing us about it yet, in that case send will
      //throw an error
      try {
        self.ws.send(data);
      } catch (e){
        debug('websocket closed before onclose event');
      }
    });
  }

  function ondrain() {
    self.writable = true;
    self.emit('drain');
  }
  // fake drain
  // defer to next tick to allow Socket to clear writeBuffer
  setTimeout(ondrain, 0);
};

/**
 * Called upon close
 *
 * @api private
 */

WS.prototype.onClose = function(){
  Transport.prototype.onClose.call(this);
};

/**
 * Closes socket.
 *
 * @api private
 */

WS.prototype.doClose = function(){
  if (typeof this.ws !== 'undefined') {
    this.ws.close();
  }
};

/**
 * Generates uri for connection.
 *
 * @api private
 */

WS.prototype.uri = function(){
  var query = this.query || {};
  var schema = this.secure ? 'wss' : 'ws';
  var port = '';

  // avoid port if default for schema
  if (this.port && (('wss' == schema && this.port != 443)
    || ('ws' == schema && this.port != 80))) {
    port = ':' + this.port;
  }

  // append timestamp to URI
  if (this.timestampRequests) {
    query[this.timestampParam] = +new Date;
  }

  // communicate binary support capabilities
  if (!this.supportsBinary) {
    query.b64 = 1;
  }

  query = parseqs.encode(query);

  // prepend ? to query
  if (query.length) {
    query = '?' + query;
  }

  return schema + '://' + this.hostname + port + this.path + query;
};

/**
 * Feature detection for WebSocket.
 *
 * @return {Boolean} whether this transport is available.
 * @api public
 */

WS.prototype.check = function(){
  return !!WebSocket && !('__initialize' in WebSocket && this.name === WS.prototype.name);
};

},{"../transport":14,"component-inherit":21,"debug":22,"engine.io-parser":25,"parseqs":35,"ws":37}],20:[function(_dereq_,module,exports){
// browser shim for xmlhttprequest module
var hasCORS = _dereq_('has-cors');

module.exports = function(opts) {
  var xdomain = opts.xdomain;

  // scheme must be same when usign XDomainRequest
  // http://blogs.msdn.com/b/ieinternals/archive/2010/05/13/xdomainrequest-restrictions-limitations-and-workarounds.aspx
  var xscheme = opts.xscheme;

  // XDomainRequest has a flow of not sending cookie, therefore it should be disabled as a default.
  // https://github.com/Automattic/engine.io-client/pull/217
  var enablesXDR = opts.enablesXDR;

  // XMLHttpRequest can be disabled on IE
  try {
    if ('undefined' != typeof XMLHttpRequest && (!xdomain || hasCORS)) {
      return new XMLHttpRequest();
    }
  } catch (e) { }

  // Use XDomainRequest for IE8 if enablesXDR is true
  // because loading bar keeps flashing when using jsonp-polling
  // https://github.com/yujiosaka/socke.io-ie8-loading-example
  try {
    if ('undefined' != typeof XDomainRequest && !xscheme && enablesXDR) {
      return new XDomainRequest();
    }
  } catch (e) { }

  if (!xdomain) {
    try {
      return new ActiveXObject('Microsoft.XMLHTTP');
    } catch(e) { }
  }
}

},{"has-cors":40}],21:[function(_dereq_,module,exports){

module.exports = function(a, b){
  var fn = function(){};
  fn.prototype = b.prototype;
  a.prototype = new fn;
  a.prototype.constructor = a;
};
},{}],22:[function(_dereq_,module,exports){

/**
 * This is the web browser implementation of `debug()`.
 *
 * Expose `debug()` as the module.
 */

exports = module.exports = _dereq_('./debug');
exports.log = log;
exports.formatArgs = formatArgs;
exports.save = save;
exports.load = load;
exports.useColors = useColors;

/**
 * Colors.
 */

exports.colors = [
  'lightseagreen',
  'forestgreen',
  'goldenrod',
  'dodgerblue',
  'darkorchid',
  'crimson'
];

/**
 * Currently only WebKit-based Web Inspectors, Firefox >= v31,
 * and the Firebug extension (any Firefox version) are known
 * to support "%c" CSS customizations.
 *
 * TODO: add a `localStorage` variable to explicitly enable/disable colors
 */

function useColors() {
  // is webkit? http://stackoverflow.com/a/16459606/376773
  return ('WebkitAppearance' in document.documentElement.style) ||
    // is firebug? http://stackoverflow.com/a/398120/376773
    (window.console && (console.firebug || (console.exception && console.table))) ||
    // is firefox >= v31?
    // https://developer.mozilla.org/en-US/docs/Tools/Web_Console#Styling_messages
    (navigator.userAgent.toLowerCase().match(/firefox\/(\d+)/) && parseInt(RegExp.$1, 10) >= 31);
}

/**
 * Map %j to `JSON.stringify()`, since no Web Inspectors do that by default.
 */

exports.formatters.j = function(v) {
  return JSON.stringify(v);
};


/**
 * Colorize log arguments if enabled.
 *
 * @api public
 */

function formatArgs() {
  var args = arguments;
  var useColors = this.useColors;

  args[0] = (useColors ? '%c' : '')
    + this.namespace
    + (useColors ? ' %c' : ' ')
    + args[0]
    + (useColors ? '%c ' : ' ')
    + '+' + exports.humanize(this.diff);

  if (!useColors) return args;

  var c = 'color: ' + this.color;
  args = [args[0], c, 'color: inherit'].concat(Array.prototype.slice.call(args, 1));

  // the final "%c" is somewhat tricky, because there could be other
  // arguments passed either before or after the %c, so we need to
  // figure out the correct index to insert the CSS into
  var index = 0;
  var lastC = 0;
  args[0].replace(/%[a-z%]/g, function(match) {
    if ('%' === match) return;
    index++;
    if ('%c' === match) {
      // we only are interested in the *last* %c
      // (the user may have provided their own)
      lastC = index;
    }
  });

  args.splice(lastC, 0, c);
  return args;
}

/**
 * Invokes `console.log()` when available.
 * No-op when `console.log` is not a "function".
 *
 * @api public
 */

function log() {
  // This hackery is required for IE8,
  // where the `console.log` function doesn't have 'apply'
  return 'object' == typeof console
    && 'function' == typeof console.log
    && Function.prototype.apply.call(console.log, console, arguments);
}

/**
 * Save `namespaces`.
 *
 * @param {String} namespaces
 * @api private
 */

function save(namespaces) {
  try {
    if (null == namespaces) {
      localStorage.removeItem('debug');
    } else {
      localStorage.debug = namespaces;
    }
  } catch(e) {}
}

/**
 * Load `namespaces`.
 *
 * @return {String} returns the previously persisted debug modes
 * @api private
 */

function load() {
  var r;
  try {
    r = localStorage.debug;
  } catch(e) {}
  return r;
}

/**
 * Enable namespaces listed in `localStorage.debug` initially.
 */

exports.enable(load());

},{"./debug":23}],23:[function(_dereq_,module,exports){

/**
 * This is the common logic for both the Node.js and web browser
 * implementations of `debug()`.
 *
 * Expose `debug()` as the module.
 */

exports = module.exports = debug;
exports.coerce = coerce;
exports.disable = disable;
exports.enable = enable;
exports.enabled = enabled;
exports.humanize = _dereq_('ms');

/**
 * The currently active debug mode names, and names to skip.
 */

exports.names = [];
exports.skips = [];

/**
 * Map of special "%n" handling functions, for the debug "format" argument.
 *
 * Valid key names are a single, lowercased letter, i.e. "n".
 */

exports.formatters = {};

/**
 * Previously assigned color.
 */

var prevColor = 0;

/**
 * Previous log timestamp.
 */

var prevTime;

/**
 * Select a color.
 *
 * @return {Number}
 * @api private
 */

function selectColor() {
  return exports.colors[prevColor++ % exports.colors.length];
}

/**
 * Create a debugger with the given `namespace`.
 *
 * @param {String} namespace
 * @return {Function}
 * @api public
 */

function debug(namespace) {

  // define the `disabled` version
  function disabled() {
  }
  disabled.enabled = false;

  // define the `enabled` version
  function enabled() {

    var self = enabled;

    // set `diff` timestamp
    var curr = +new Date();
    var ms = curr - (prevTime || curr);
    self.diff = ms;
    self.prev = prevTime;
    self.curr = curr;
    prevTime = curr;

    // add the `color` if not set
    if (null == self.useColors) self.useColors = exports.useColors();
    if (null == self.color && self.useColors) self.color = selectColor();

    var args = Array.prototype.slice.call(arguments);

    args[0] = exports.coerce(args[0]);

    if ('string' !== typeof args[0]) {
      // anything else let's inspect with %o
      args = ['%o'].concat(args);
    }

    // apply any `formatters` transformations
    var index = 0;
    args[0] = args[0].replace(/%([a-z%])/g, function(match, format) {
      // if we encounter an escaped % then don't increase the array index
      if (match === '%') return match;
      index++;
      var formatter = exports.formatters[format];
      if ('function' === typeof formatter) {
        var val = args[index];
        match = formatter.call(self, val);

        // now we need to remove `args[index]` since it's inlined in the `format`
        args.splice(index, 1);
        index--;
      }
      return match;
    });

    if ('function' === typeof exports.formatArgs) {
      args = exports.formatArgs.apply(self, args);
    }
    var logFn = enabled.log || exports.log || console.log.bind(console);
    logFn.apply(self, args);
  }
  enabled.enabled = true;

  var fn = exports.enabled(namespace) ? enabled : disabled;

  fn.namespace = namespace;

  return fn;
}

/**
 * Enables a debug mode by namespaces. This can include modes
 * separated by a colon and wildcards.
 *
 * @param {String} namespaces
 * @api public
 */

function enable(namespaces) {
  exports.save(namespaces);

  var split = (namespaces || '').split(/[\s,]+/);
  var len = split.length;

  for (var i = 0; i < len; i++) {
    if (!split[i]) continue; // ignore empty strings
    namespaces = split[i].replace(/\*/g, '.*?');
    if (namespaces[0] === '-') {
      exports.skips.push(new RegExp('^' + namespaces.substr(1) + '$'));
    } else {
      exports.names.push(new RegExp('^' + namespaces + '$'));
    }
  }
}

/**
 * Disable debug output.
 *
 * @api public
 */

function disable() {
  exports.enable('');
}

/**
 * Returns true if the given mode name is enabled, false otherwise.
 *
 * @param {String} name
 * @return {Boolean}
 * @api public
 */

function enabled(name) {
  var i, len;
  for (i = 0, len = exports.skips.length; i < len; i++) {
    if (exports.skips[i].test(name)) {
      return false;
    }
  }
  for (i = 0, len = exports.names.length; i < len; i++) {
    if (exports.names[i].test(name)) {
      return true;
    }
  }
  return false;
}

/**
 * Coerce `val`.
 *
 * @param {Mixed} val
 * @return {Mixed}
 * @api private
 */

function coerce(val) {
  if (val instanceof Error) return val.stack || val.message;
  return val;
}

},{"ms":24}],24:[function(_dereq_,module,exports){
/**
 * Helpers.
 */

var s = 1000;
var m = s * 60;
var h = m * 60;
var d = h * 24;
var y = d * 365.25;

/**
 * Parse or format the given `val`.
 *
 * Options:
 *
 *  - `long` verbose formatting [false]
 *
 * @param {String|Number} val
 * @param {Object} options
 * @return {String|Number}
 * @api public
 */

module.exports = function(val, options){
  options = options || {};
  if ('string' == typeof val) return parse(val);
  return options.long
    ? long(val)
    : short(val);
};

/**
 * Parse the given `str` and return milliseconds.
 *
 * @param {String} str
 * @return {Number}
 * @api private
 */

function parse(str) {
  var match = /^((?:\d+)?\.?\d+) *(ms|seconds?|s|minutes?|m|hours?|h|days?|d|years?|y)?$/i.exec(str);
  if (!match) return;
  var n = parseFloat(match[1]);
  var type = (match[2] || 'ms').toLowerCase();
  switch (type) {
    case 'years':
    case 'year':
    case 'y':
      return n * y;
    case 'days':
    case 'day':
    case 'd':
      return n * d;
    case 'hours':
    case 'hour':
    case 'h':
      return n * h;
    case 'minutes':
    case 'minute':
    case 'm':
      return n * m;
    case 'seconds':
    case 'second':
    case 's':
      return n * s;
    case 'ms':
      return n;
  }
}

/**
 * Short format for `ms`.
 *
 * @param {Number} ms
 * @return {String}
 * @api private
 */

function short(ms) {
  if (ms >= d) return Math.round(ms / d) + 'd';
  if (ms >= h) return Math.round(ms / h) + 'h';
  if (ms >= m) return Math.round(ms / m) + 'm';
  if (ms >= s) return Math.round(ms / s) + 's';
  return ms + 'ms';
}

/**
 * Long format for `ms`.
 *
 * @param {Number} ms
 * @return {String}
 * @api private
 */

function long(ms) {
  return plural(ms, d, 'day')
    || plural(ms, h, 'hour')
    || plural(ms, m, 'minute')
    || plural(ms, s, 'second')
    || ms + ' ms';
}

/**
 * Pluralization helper.
 */

function plural(ms, n, name) {
  if (ms < n) return;
  if (ms < n * 1.5) return Math.floor(ms / n) + ' ' + name;
  return Math.ceil(ms / n) + ' ' + name + 's';
}

},{}],25:[function(_dereq_,module,exports){
(function (global){
/**
 * Module dependencies.
 */

var keys = _dereq_('./keys');
var hasBinary = _dereq_('has-binary');
var sliceBuffer = _dereq_('arraybuffer.slice');
var base64encoder = _dereq_('base64-arraybuffer');
var after = _dereq_('after');
var utf8 = _dereq_('utf8');

/**
 * Check if we are running an android browser. That requires us to use
 * ArrayBuffer with polling transports...
 *
 * http://ghinda.net/jpeg-blob-ajax-android/
 */

var isAndroid = navigator.userAgent.match(/Android/i);

/**
 * Check if we are running in PhantomJS.
 * Uploading a Blob with PhantomJS does not work correctly, as reported here:
 * https://github.com/ariya/phantomjs/issues/11395
 * @type boolean
 */
var isPhantomJS = /PhantomJS/i.test(navigator.userAgent);

/**
 * When true, avoids using Blobs to encode payloads.
 * @type boolean
 */
var dontSendBlobs = isAndroid || isPhantomJS;

/**
 * Current protocol version.
 */

exports.protocol = 3;

/**
 * Packet types.
 */

var packets = exports.packets = {
    open:     0    // non-ws
  , close:    1    // non-ws
  , ping:     2
  , pong:     3
  , message:  4
  , upgrade:  5
  , noop:     6
};

var packetslist = keys(packets);

/**
 * Premade error packet.
 */

var err = { type: 'error', data: 'parser error' };

/**
 * Create a blob api even for blob builder when vendor prefixes exist
 */

var Blob = _dereq_('blob');

/**
 * Encodes a packet.
 *
 *     <packet type id> [ <data> ]
 *
 * Example:
 *
 *     5hello world
 *     3
 *     4
 *
 * Binary is encoded in an identical principle
 *
 * @api private
 */

exports.encodePacket = function (packet, supportsBinary, utf8encode, callback) {
  if ('function' == typeof supportsBinary) {
    callback = supportsBinary;
    supportsBinary = false;
  }

  if ('function' == typeof utf8encode) {
    callback = utf8encode;
    utf8encode = null;
  }

  var data = (packet.data === undefined)
    ? undefined
    : packet.data.buffer || packet.data;

  if (global.ArrayBuffer && data instanceof ArrayBuffer) {
    return encodeArrayBuffer(packet, supportsBinary, callback);
  } else if (Blob && data instanceof global.Blob) {
    return encodeBlob(packet, supportsBinary, callback);
  }

  // might be an object with { base64: true, data: dataAsBase64String }
  if (data && data.base64) {
    return encodeBase64Object(packet, callback);
  }

  // Sending data as a utf-8 string
  var encoded = packets[packet.type];

  // data fragment is optional
  if (undefined !== packet.data) {
    encoded += utf8encode ? utf8.encode(String(packet.data)) : String(packet.data);
  }

  return callback('' + encoded);

};

function encodeBase64Object(packet, callback) {
  // packet data is an object { base64: true, data: dataAsBase64String }
  var message = 'b' + exports.packets[packet.type] + packet.data.data;
  return callback(message);
}

/**
 * Encode packet helpers for binary types
 */

function encodeArrayBuffer(packet, supportsBinary, callback) {
  if (!supportsBinary) {
    return exports.encodeBase64Packet(packet, callback);
  }

  var data = packet.data;
  var contentArray = new Uint8Array(data);
  var resultBuffer = new Uint8Array(1 + data.byteLength);

  resultBuffer[0] = packets[packet.type];
  for (var i = 0; i < contentArray.length; i++) {
    resultBuffer[i+1] = contentArray[i];
  }

  return callback(resultBuffer.buffer);
}

function encodeBlobAsArrayBuffer(packet, supportsBinary, callback) {
  if (!supportsBinary) {
    return exports.encodeBase64Packet(packet, callback);
  }

  var fr = new FileReader();
  fr.onload = function() {
    packet.data = fr.result;
    exports.encodePacket(packet, supportsBinary, true, callback);
  };
  return fr.readAsArrayBuffer(packet.data);
}

function encodeBlob(packet, supportsBinary, callback) {
  if (!supportsBinary) {
    return exports.encodeBase64Packet(packet, callback);
  }

  if (dontSendBlobs) {
    return encodeBlobAsArrayBuffer(packet, supportsBinary, callback);
  }

  var length = new Uint8Array(1);
  length[0] = packets[packet.type];
  var blob = new Blob([length.buffer, packet.data]);

  return callback(blob);
}

/**
 * Encodes a packet with binary data in a base64 string
 *
 * @param {Object} packet, has `type` and `data`
 * @return {String} base64 encoded message
 */

exports.encodeBase64Packet = function(packet, callback) {
  var message = 'b' + exports.packets[packet.type];
  if (Blob && packet.data instanceof Blob) {
    var fr = new FileReader();
    fr.onload = function() {
      var b64 = fr.result.split(',')[1];
      callback(message + b64);
    };
    return fr.readAsDataURL(packet.data);
  }

  var b64data;
  try {
    b64data = String.fromCharCode.apply(null, new Uint8Array(packet.data));
  } catch (e) {
    // iPhone Safari doesn't let you apply with typed arrays
    var typed = new Uint8Array(packet.data);
    var basic = new Array(typed.length);
    for (var i = 0; i < typed.length; i++) {
      basic[i] = typed[i];
    }
    b64data = String.fromCharCode.apply(null, basic);
  }
  message += global.btoa(b64data);
  return callback(message);
};

/**
 * Decodes a packet. Changes format to Blob if requested.
 *
 * @return {Object} with `type` and `data` (if any)
 * @api private
 */

exports.decodePacket = function (data, binaryType, utf8decode) {
  // String data
  if (typeof data == 'string' || data === undefined) {
    if (data.charAt(0) == 'b') {
      return exports.decodeBase64Packet(data.substr(1), binaryType);
    }

    if (utf8decode) {
      try {
        data = utf8.decode(data);
      } catch (e) {
        return err;
      }
    }
    var type = data.charAt(0);

    if (Number(type) != type || !packetslist[type]) {
      return err;
    }

    if (data.length > 1) {
      return { type: packetslist[type], data: data.substring(1) };
    } else {
      return { type: packetslist[type] };
    }
  }

  var asArray = new Uint8Array(data);
  var type = asArray[0];
  var rest = sliceBuffer(data, 1);
  if (Blob && binaryType === 'blob') {
    rest = new Blob([rest]);
  }
  return { type: packetslist[type], data: rest };
};

/**
 * Decodes a packet encoded in a base64 string
 *
 * @param {String} base64 encoded message
 * @return {Object} with `type` and `data` (if any)
 */

exports.decodeBase64Packet = function(msg, binaryType) {
  var type = packetslist[msg.charAt(0)];
  if (!global.ArrayBuffer) {
    return { type: type, data: { base64: true, data: msg.substr(1) } };
  }

  var data = base64encoder.decode(msg.substr(1));

  if (binaryType === 'blob' && Blob) {
    data = new Blob([data]);
  }

  return { type: type, data: data };
};

/**
 * Encodes multiple messages (payload).
 *
 *     <length>:data
 *
 * Example:
 *
 *     11:hello world2:hi
 *
 * If any contents are binary, they will be encoded as base64 strings. Base64
 * encoded strings are marked with a b before the length specifier
 *
 * @param {Array} packets
 * @api private
 */

exports.encodePayload = function (packets, supportsBinary, callback) {
  if (typeof supportsBinary == 'function') {
    callback = supportsBinary;
    supportsBinary = null;
  }

  var isBinary = hasBinary(packets);

  if (supportsBinary && isBinary) {
    if (Blob && !dontSendBlobs) {
      return exports.encodePayloadAsBlob(packets, callback);
    }

    return exports.encodePayloadAsArrayBuffer(packets, callback);
  }

  if (!packets.length) {
    return callback('0:');
  }

  function setLengthHeader(message) {
    return message.length + ':' + message;
  }

  function encodeOne(packet, doneCallback) {
    exports.encodePacket(packet, !isBinary ? false : supportsBinary, true, function(message) {
      doneCallback(null, setLengthHeader(message));
    });
  }

  map(packets, encodeOne, function(err, results) {
    return callback(results.join(''));
  });
};

/**
 * Async array map using after
 */

function map(ary, each, done) {
  var result = new Array(ary.length);
  var next = after(ary.length, done);

  var eachWithIndex = function(i, el, cb) {
    each(el, function(error, msg) {
      result[i] = msg;
      cb(error, result);
    });
  };

  for (var i = 0; i < ary.length; i++) {
    eachWithIndex(i, ary[i], next);
  }
}

/*
 * Decodes data when a payload is maybe expected. Possible binary contents are
 * decoded from their base64 representation
 *
 * @param {String} data, callback method
 * @api public
 */

exports.decodePayload = function (data, binaryType, callback) {
  if (typeof data != 'string') {
    return exports.decodePayloadAsBinary(data, binaryType, callback);
  }

  if (typeof binaryType === 'function') {
    callback = binaryType;
    binaryType = null;
  }

  var packet;
  if (data == '') {
    // parser error - ignoring payload
    return callback(err, 0, 1);
  }

  var length = ''
    , n, msg;

  for (var i = 0, l = data.length; i < l; i++) {
    var chr = data.charAt(i);

    if (':' != chr) {
      length += chr;
    } else {
      if ('' == length || (length != (n = Number(length)))) {
        // parser error - ignoring payload
        return callback(err, 0, 1);
      }

      msg = data.substr(i + 1, n);

      if (length != msg.length) {
        // parser error - ignoring payload
        return callback(err, 0, 1);
      }

      if (msg.length) {
        packet = exports.decodePacket(msg, binaryType, true);

        if (err.type == packet.type && err.data == packet.data) {
          // parser error in individual packet - ignoring payload
          return callback(err, 0, 1);
        }

        var ret = callback(packet, i + n, l);
        if (false === ret) return;
      }

      // advance cursor
      i += n;
      length = '';
    }
  }

  if (length != '') {
    // parser error - ignoring payload
    return callback(err, 0, 1);
  }

};

/**
 * Encodes multiple messages (payload) as binary.
 *
 * <1 = binary, 0 = string><number from 0-9><number from 0-9>[...]<number
 * 255><data>
 *
 * Example:
 * 1 3 255 1 2 3, if the binary contents are interpreted as 8 bit integers
 *
 * @param {Array} packets
 * @return {ArrayBuffer} encoded payload
 * @api private
 */

exports.encodePayloadAsArrayBuffer = function(packets, callback) {
  if (!packets.length) {
    return callback(new ArrayBuffer(0));
  }

  function encodeOne(packet, doneCallback) {
    exports.encodePacket(packet, true, true, function(data) {
      return doneCallback(null, data);
    });
  }

  map(packets, encodeOne, function(err, encodedPackets) {
    var totalLength = encodedPackets.reduce(function(acc, p) {
      var len;
      if (typeof p === 'string'){
        len = p.length;
      } else {
        len = p.byteLength;
      }
      return acc + len.toString().length + len + 2; // string/binary identifier + separator = 2
    }, 0);

    var resultArray = new Uint8Array(totalLength);

    var bufferIndex = 0;
    encodedPackets.forEach(function(p) {
      var isString = typeof p === 'string';
      var ab = p;
      if (isString) {
        var view = new Uint8Array(p.length);
        for (var i = 0; i < p.length; i++) {
          view[i] = p.charCodeAt(i);
        }
        ab = view.buffer;
      }

      if (isString) { // not true binary
        resultArray[bufferIndex++] = 0;
      } else { // true binary
        resultArray[bufferIndex++] = 1;
      }

      var lenStr = ab.byteLength.toString();
      for (var i = 0; i < lenStr.length; i++) {
        resultArray[bufferIndex++] = parseInt(lenStr[i]);
      }
      resultArray[bufferIndex++] = 255;

      var view = new Uint8Array(ab);
      for (var i = 0; i < view.length; i++) {
        resultArray[bufferIndex++] = view[i];
      }
    });

    return callback(resultArray.buffer);
  });
};

/**
 * Encode as Blob
 */

exports.encodePayloadAsBlob = function(packets, callback) {
  function encodeOne(packet, doneCallback) {
    exports.encodePacket(packet, true, true, function(encoded) {
      var binaryIdentifier = new Uint8Array(1);
      binaryIdentifier[0] = 1;
      if (typeof encoded === 'string') {
        var view = new Uint8Array(encoded.length);
        for (var i = 0; i < encoded.length; i++) {
          view[i] = encoded.charCodeAt(i);
        }
        encoded = view.buffer;
        binaryIdentifier[0] = 0;
      }

      var len = (encoded instanceof ArrayBuffer)
        ? encoded.byteLength
        : encoded.size;

      var lenStr = len.toString();
      var lengthAry = new Uint8Array(lenStr.length + 1);
      for (var i = 0; i < lenStr.length; i++) {
        lengthAry[i] = parseInt(lenStr[i]);
      }
      lengthAry[lenStr.length] = 255;

      if (Blob) {
        var blob = new Blob([binaryIdentifier.buffer, lengthAry.buffer, encoded]);
        doneCallback(null, blob);
      }
    });
  }

  map(packets, encodeOne, function(err, results) {
    return callback(new Blob(results));
  });
};

/*
 * Decodes data when a payload is maybe expected. Strings are decoded by
 * interpreting each byte as a key code for entries marked to start with 0. See
 * description of encodePayloadAsBinary
 *
 * @param {ArrayBuffer} data, callback method
 * @api public
 */

exports.decodePayloadAsBinary = function (data, binaryType, callback) {
  if (typeof binaryType === 'function') {
    callback = binaryType;
    binaryType = null;
  }

  var bufferTail = data;
  var buffers = [];

  var numberTooLong = false;
  while (bufferTail.byteLength > 0) {
    var tailArray = new Uint8Array(bufferTail);
    var isString = tailArray[0] === 0;
    var msgLength = '';

    for (var i = 1; ; i++) {
      if (tailArray[i] == 255) break;

      if (msgLength.length > 310) {
        numberTooLong = true;
        break;
      }

      msgLength += tailArray[i];
    }

    if(numberTooLong) return callback(err, 0, 1);

    bufferTail = sliceBuffer(bufferTail, 2 + msgLength.length);
    msgLength = parseInt(msgLength);

    var msg = sliceBuffer(bufferTail, 0, msgLength);
    if (isString) {
      try {
        msg = String.fromCharCode.apply(null, new Uint8Array(msg));
      } catch (e) {
        // iPhone Safari doesn't let you apply to typed arrays
        var typed = new Uint8Array(msg);
        msg = '';
        for (var i = 0; i < typed.length; i++) {
          msg += String.fromCharCode(typed[i]);
        }
      }
    }

    buffers.push(msg);
    bufferTail = sliceBuffer(bufferTail, msgLength);
  }

  var total = buffers.length;
  buffers.forEach(function(buffer, i) {
    callback(exports.decodePacket(buffer, binaryType, true), i, total);
  });
};

}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"./keys":26,"after":27,"arraybuffer.slice":28,"base64-arraybuffer":29,"blob":30,"has-binary":31,"utf8":33}],26:[function(_dereq_,module,exports){

/**
 * Gets the keys for an object.
 *
 * @return {Array} keys
 * @api private
 */

module.exports = Object.keys || function keys (obj){
  var arr = [];
  var has = Object.prototype.hasOwnProperty;

  for (var i in obj) {
    if (has.call(obj, i)) {
      arr.push(i);
    }
  }
  return arr;
};

},{}],27:[function(_dereq_,module,exports){
module.exports = after

function after(count, callback, err_cb) {
    var bail = false
    err_cb = err_cb || noop
    proxy.count = count

    return (count === 0) ? callback() : proxy

    function proxy(err, result) {
        if (proxy.count <= 0) {
            throw new Error('after called too many times')
        }
        --proxy.count

        // after first error, rest are passed to err_cb
        if (err) {
            bail = true
            callback(err)
            // future error callbacks will go to error handler
            callback = err_cb
        } else if (proxy.count === 0 && !bail) {
            callback(null, result)
        }
    }
}

function noop() {}

},{}],28:[function(_dereq_,module,exports){
/**
 * An abstraction for slicing an arraybuffer even when
 * ArrayBuffer.prototype.slice is not supported
 *
 * @api public
 */

module.exports = function(arraybuffer, start, end) {
  var bytes = arraybuffer.byteLength;
  start = start || 0;
  end = end || bytes;

  if (arraybuffer.slice) { return arraybuffer.slice(start, end); }

  if (start < 0) { start += bytes; }
  if (end < 0) { end += bytes; }
  if (end > bytes) { end = bytes; }

  if (start >= bytes || start >= end || bytes === 0) {
    return new ArrayBuffer(0);
  }

  var abv = new Uint8Array(arraybuffer);
  var result = new Uint8Array(end - start);
  for (var i = start, ii = 0; i < end; i++, ii++) {
    result[ii] = abv[i];
  }
  return result.buffer;
};

},{}],29:[function(_dereq_,module,exports){
/*
 * base64-arraybuffer
 * https://github.com/niklasvh/base64-arraybuffer
 *
 * Copyright (c) 2012 Niklas von Hertzen
 * Licensed under the MIT license.
 */
(function(chars){
  "use strict";

  exports.encode = function(arraybuffer) {
    var bytes = new Uint8Array(arraybuffer),
    i, len = bytes.length, base64 = "";

    for (i = 0; i < len; i+=3) {
      base64 += chars[bytes[i] >> 2];
      base64 += chars[((bytes[i] & 3) << 4) | (bytes[i + 1] >> 4)];
      base64 += chars[((bytes[i + 1] & 15) << 2) | (bytes[i + 2] >> 6)];
      base64 += chars[bytes[i + 2] & 63];
    }

    if ((len % 3) === 2) {
      base64 = base64.substring(0, base64.length - 1) + "=";
    } else if (len % 3 === 1) {
      base64 = base64.substring(0, base64.length - 2) + "==";
    }

    return base64;
  };

  exports.decode =  function(base64) {
    var bufferLength = base64.length * 0.75,
    len = base64.length, i, p = 0,
    encoded1, encoded2, encoded3, encoded4;

    if (base64[base64.length - 1] === "=") {
      bufferLength--;
      if (base64[base64.length - 2] === "=") {
        bufferLength--;
      }
    }

    var arraybuffer = new ArrayBuffer(bufferLength),
    bytes = new Uint8Array(arraybuffer);

    for (i = 0; i < len; i+=4) {
      encoded1 = chars.indexOf(base64[i]);
      encoded2 = chars.indexOf(base64[i+1]);
      encoded3 = chars.indexOf(base64[i+2]);
      encoded4 = chars.indexOf(base64[i+3]);

      bytes[p++] = (encoded1 << 2) | (encoded2 >> 4);
      bytes[p++] = ((encoded2 & 15) << 4) | (encoded3 >> 2);
      bytes[p++] = ((encoded3 & 3) << 6) | (encoded4 & 63);
    }

    return arraybuffer;
  };
})("https://cdn4.tgdd.vn/v2015/Scripts/desktop/V5/ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/");

},{}],30:[function(_dereq_,module,exports){
(function (global){
/**
 * Create a blob builder even when vendor prefixes exist
 */

var BlobBuilder = global.BlobBuilder
  || global.WebKitBlobBuilder
  || global.MSBlobBuilder
  || global.MozBlobBuilder;

/**
 * Check if Blob constructor is supported
 */

var blobSupported = (function() {
  try {
    var b = new Blob(['hi']);
    return b.size == 2;
  } catch(e) {
    return false;
  }
})();

/**
 * Check if BlobBuilder is supported
 */

var blobBuilderSupported = BlobBuilder
  && BlobBuilder.prototype.append
  && BlobBuilder.prototype.getBlob;

function BlobBuilderConstructor(ary, options) {
  options = options || {};

  var bb = new BlobBuilder();
  for (var i = 0; i < ary.length; i++) {
    bb.append(ary[i]);
  }
  return (options.type) ? bb.getBlob(options.type) : bb.getBlob();
};

module.exports = (function() {
  if (blobSupported) {
    return global.Blob;
  } else if (blobBuilderSupported) {
    return BlobBuilderConstructor;
  } else {
    return undefined;
  }
})();

}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],31:[function(_dereq_,module,exports){
(function (global){

/*
 * Module requirements.
 */

var isArray = _dereq_('isarray');

/**
 * Module exports.
 */

module.exports = hasBinary;

/**
 * Checks for binary data.
 *
 * Right now only Buffer and ArrayBuffer are supported..
 *
 * @param {Object} anything
 * @api public
 */

function hasBinary(data) {

  function _hasBinary(obj) {
    if (!obj) return false;

    if ( (global.Buffer && global.Buffer.isBuffer(obj)) ||
         (global.ArrayBuffer && obj instanceof ArrayBuffer) ||
         (global.Blob && obj instanceof Blob) ||
         (global.File && obj instanceof File)
        ) {
      return true;
    }

    if (isArray(obj)) {
      for (var i = 0; i < obj.length; i++) {
          if (_hasBinary(obj[i])) {
              return true;
          }
      }
    } else if (obj && 'object' == typeof obj) {
      if (obj.toJSON) {
        obj = obj.toJSON();
      }

      for (var key in obj) {
        if (obj.hasOwnProperty(key) && _hasBinary(obj[key])) {
          return true;
        }
      }
    }

    return false;
  }

  return _hasBinary(data);
}

}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"isarray":32}],32:[function(_dereq_,module,exports){
module.exports = Array.isArray || function (arr) {
  return Object.prototype.toString.call(arr) == '[object Array]';
};

},{}],33:[function(_dereq_,module,exports){
(function (global){
/*! http://mths.be/utf8js v2.0.0 by @mathias */
;(function(root) {

	// Detect free variables `exports`
	var freeExports = typeof exports == 'object' && exports;

	// Detect free variable `module`
	var freeModule = typeof module == 'object' && module &&
		module.exports == freeExports && module;

	// Detect free variable `global`, from Node.js or Browserified code,
	// and use it as `root`
	var freeGlobal = typeof global == 'object' && global;
	if (freeGlobal.global === freeGlobal || freeGlobal.window === freeGlobal) {
		root = freeGlobal;
	}

	/*--------------------------------------------------------------------------*/

	var stringFromCharCode = String.fromCharCode;

	// Taken from http://mths.be/punycode
	function ucs2decode(string) {
		var output = [];
		var counter = 0;
		var length = string.length;
		var value;
		var extra;
		while (counter < length) {
			value = string.charCodeAt(counter++);
			if (value >= 0xD800 && value <= 0xDBFF && counter < length) {
				// high surrogate, and there is a next character
				extra = string.charCodeAt(counter++);
				if ((extra & 0xFC00) == 0xDC00) { // low surrogate
					output.push(((value & 0x3FF) << 10) + (extra & 0x3FF) + 0x10000);
				} else {
					// unmatched surrogate; only append this code unit, in case the next
					// code unit is the high surrogate of a surrogate pair
					output.push(value);
					counter--;
				}
			} else {
				output.push(value);
			}
		}
		return output;
	}

	// Taken from http://mths.be/punycode
	function ucs2encode(array) {
		var length = array.length;
		var index = -1;
		var value;
		var output = '';
		while (++index < length) {
			value = array[index];
			if (value > 0xFFFF) {
				value -= 0x10000;
				output += stringFromCharCode(value >>> 10 & 0x3FF | 0xD800);
				value = 0xDC00 | value & 0x3FF;
			}
			output += stringFromCharCode(value);
		}
		return output;
	}

	/*--------------------------------------------------------------------------*/

	function createByte(codePoint, shift) {
		return stringFromCharCode(((codePoint >> shift) & 0x3F) | 0x80);
	}

	function encodeCodePoint(codePoint) {
		if ((codePoint & 0xFFFFFF80) == 0) { // 1-byte sequence
			return stringFromCharCode(codePoint);
		}
		var symbol = '';
		if ((codePoint & 0xFFFFF800) == 0) { // 2-byte sequence
			symbol = stringFromCharCode(((codePoint >> 6) & 0x1F) | 0xC0);
		}
		else if ((codePoint & 0xFFFF0000) == 0) { // 3-byte sequence
			symbol = stringFromCharCode(((codePoint >> 12) & 0x0F) | 0xE0);
			symbol += createByte(codePoint, 6);
		}
		else if ((codePoint & 0xFFE00000) == 0) { // 4-byte sequence
			symbol = stringFromCharCode(((codePoint >> 18) & 0x07) | 0xF0);
			symbol += createByte(codePoint, 12);
			symbol += createByte(codePoint, 6);
		}
		symbol += stringFromCharCode((codePoint & 0x3F) | 0x80);
		return symbol;
	}

	function utf8encode(string) {
		var codePoints = ucs2decode(string);

		// console.log(JSON.stringify(codePoints.map(function(x) {
		// 	return 'U+' + x.toString(16).toUpperCase();
		// })));

		var length = codePoints.length;
		var index = -1;
		var codePoint;
		var byteString = '';
		while (++index < length) {
			codePoint = codePoints[index];
			byteString += encodeCodePoint(codePoint);
		}
		return byteString;
	}

	/*--------------------------------------------------------------------------*/

	function readContinuationByte() {
		if (byteIndex >= byteCount) {
			throw Error('Invalid byte index');
		}

		var continuationByte = byteArray[byteIndex] & 0xFF;
		byteIndex++;

		if ((continuationByte & 0xC0) == 0x80) {
			return continuationByte & 0x3F;
		}

		// If we end up here, it’s not a continuation byte
		throw Error('Invalid continuation byte');
	}

	function decodeSymbol() {
		var byte1;
		var byte2;
		var byte3;
		var byte4;
		var codePoint;

		if (byteIndex > byteCount) {
			throw Error('Invalid byte index');
		}

		if (byteIndex == byteCount) {
			return false;
		}

		// Read first byte
		byte1 = byteArray[byteIndex] & 0xFF;
		byteIndex++;

		// 1-byte sequence (no continuation bytes)
		if ((byte1 & 0x80) == 0) {
			return byte1;
		}

		// 2-byte sequence
		if ((byte1 & 0xE0) == 0xC0) {
			var byte2 = readContinuationByte();
			codePoint = ((byte1 & 0x1F) << 6) | byte2;
			if (codePoint >= 0x80) {
				return codePoint;
			} else {
				throw Error('Invalid continuation byte');
			}
		}

		// 3-byte sequence (may include unpaired surrogates)
		if ((byte1 & 0xF0) == 0xE0) {
			byte2 = readContinuationByte();
			byte3 = readContinuationByte();
			codePoint = ((byte1 & 0x0F) << 12) | (byte2 << 6) | byte3;
			if (codePoint >= 0x0800) {
				return codePoint;
			} else {
				throw Error('Invalid continuation byte');
			}
		}

		// 4-byte sequence
		if ((byte1 & 0xF8) == 0xF0) {
			byte2 = readContinuationByte();
			byte3 = readContinuationByte();
			byte4 = readContinuationByte();
			codePoint = ((byte1 & 0x0F) << 0x12) | (byte2 << 0x0C) |
				(byte3 << 0x06) | byte4;
			if (codePoint >= 0x010000 && codePoint <= 0x10FFFF) {
				return codePoint;
			}
		}

		throw Error('Invalid UTF-8 detected');
	}

	var byteArray;
	var byteCount;
	var byteIndex;
	function utf8decode(byteString) {
		byteArray = ucs2decode(byteString);
		byteCount = byteArray.length;
		byteIndex = 0;
		var codePoints = [];
		var tmp;
		while ((tmp = decodeSymbol()) !== false) {
			codePoints.push(tmp);
		}
		return ucs2encode(codePoints);
	}

	/*--------------------------------------------------------------------------*/

	var utf8 = {
		'version': '2.0.0',
		'encode': utf8encode,
		'decode': utf8decode
	};

	// Some AMD build optimizers, like r.js, check for specific condition patterns
	// like the following:
	if (
		typeof define == 'function' &&
		typeof define.amd == 'object' &&
		define.amd
	) {
		define(function() {
			return utf8;
		});
	}	else if (freeExports && !freeExports.nodeType) {
		if (freeModule) { // in Node.js or RingoJS v0.8.0+
			freeModule.exports = utf8;
		} else { // in Narwhal or RingoJS v0.7.0-
			var object = {};
			var hasOwnProperty = object.hasOwnProperty;
			for (var key in utf8) {
				hasOwnProperty.call(utf8, key) && (freeExports[key] = utf8[key]);
			}
		}
	} else { // in Rhino or a web browser
		root.utf8 = utf8;
	}

}(this));

}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],34:[function(_dereq_,module,exports){
(function (global){
/**
 * JSON parse.
 *
 * @see Based on jQuery#parseJSON (MIT) and JSON2
 * @api private
 */

var rvalidchars = /^[\],:{}\s]*$/;
var rvalidescape = /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g;
var rvalidtokens = /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g;
var rvalidbraces = /(?:^|:|,)(?:\s*\[)+/g;
var rtrimLeft = /^\s+/;
var rtrimRight = /\s+$/;

module.exports = function parsejson(data) {
  if ('string' != typeof data || !data) {
    return null;
  }

  data = data.replace(rtrimLeft, '').replace(rtrimRight, '');

  // Attempt to parse using the native JSON parser first
  if (global.JSON && JSON.parse) {
    return JSON.parse(data);
  }

  if (rvalidchars.test(data.replace(rvalidescape, '@')
      .replace(rvalidtokens, ']')
      .replace(rvalidbraces, ''))) {
    return (new Function('return ' + data))();
  }
};
}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],35:[function(_dereq_,module,exports){
/**
 * Compiles a querystring
 * Returns string representation of the object
 *
 * @param {Object}
 * @api private
 */

exports.encode = function (obj) {
  var str = '';

  for (var i in obj) {
    if (obj.hasOwnProperty(i)) {
      if (str.length) str += '&';
      str += encodeURIComponent(i) + '=' + encodeURIComponent(obj[i]);
    }
  }

  return str;
};

/**
 * Parses a simple querystring into an object
 *
 * @param {String} qs
 * @api private
 */

exports.decode = function(qs){
  var qry = {};
  var pairs = qs.split('&');
  for (var i = 0, l = pairs.length; i < l; i++) {
    var pair = pairs[i].split('=');
    qry[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
  }
  return qry;
};

},{}],36:[function(_dereq_,module,exports){
/**
 * Parses an URI
 *
 * @author Steven Levithan <stevenlevithan.com> (MIT license)
 * @api private
 */

var re = /^(?:(?![^:@]+:[^:@\/]*@)(http|https|ws|wss):\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?((?:[a-f0-9]{0,4}:){2,7}[a-f0-9]{0,4}|[^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/;

var parts = [
    'source', 'protocol', 'authority', 'userInfo', 'user', 'password', 'host', 'port', 'relative', 'path', 'directory', 'file', 'query', 'anchor'
];

module.exports = function parseuri(str) {
    var src = str,
        b = str.indexOf('['),
        e = str.indexOf(']');

    if (b != -1 && e != -1) {
        str = str.substring(0, b) + str.substring(b, e).replace(/:/g, ';') + str.substring(e, str.length);
    }

    var m = re.exec(str || ''),
        uri = {},
        i = 14;

    while (i--) {
        uri[parts[i]] = m[i] || '';
    }

    if (b != -1 && e != -1) {
        uri.source = src;
        uri.host = uri.host.substring(1, uri.host.length - 1).replace(/;/g, ':');
        uri.authority = uri.authority.replace('[', '').replace(']', '').replace(/;/g, ':');
        uri.ipv6uri = true;
    }

    return uri;
};

},{}],37:[function(_dereq_,module,exports){

/**
 * Module dependencies.
 */

var global = (function() { return this; })();

/**
 * WebSocket constructor.
 */

var WebSocket = global.WebSocket || global.MozWebSocket;

/**
 * Module exports.
 */

module.exports = WebSocket ? ws : null;

/**
 * WebSocket constructor.
 *
 * The third `opts` options object gets ignored in web browsers, since it's
 * non-standard, and throws a TypeError if passed to the constructor.
 * See: https://github.com/einaros/ws/issues/227
 *
 * @param {String} uri
 * @param {Array} protocols (optional)
 * @param {Object) opts (optional)
 * @api public
 */

function ws(uri, protocols, opts) {
  var instance;
  if (protocols) {
    instance = new WebSocket(uri, protocols);
  } else {
    instance = new WebSocket(uri);
  }
  return instance;
}

if (WebSocket) ws.prototype = WebSocket.prototype;

},{}],38:[function(_dereq_,module,exports){
(function (global){

/*
 * Module requirements.
 */

var isArray = _dereq_('isarray');

/**
 * Module exports.
 */

module.exports = hasBinary;

/**
 * Checks for binary data.
 *
 * Right now only Buffer and ArrayBuffer are supported..
 *
 * @param {Object} anything
 * @api public
 */

function hasBinary(data) {

  function _hasBinary(obj) {
    if (!obj) return false;

    if ( (global.Buffer && global.Buffer.isBuffer(obj)) ||
         (global.ArrayBuffer && obj instanceof ArrayBuffer) ||
         (global.Blob && obj instanceof Blob) ||
         (global.File && obj instanceof File)
        ) {
      return true;
    }

    if (isArray(obj)) {
      for (var i = 0; i < obj.length; i++) {
          if (_hasBinary(obj[i])) {
              return true;
          }
      }
    } else if (obj && 'object' == typeof obj) {
      if (obj.toJSON) {
        obj = obj.toJSON();
      }

      for (var key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key) && _hasBinary(obj[key])) {
          return true;
        }
      }
    }

    return false;
  }

  return _hasBinary(data);
}

}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"isarray":39}],39:[function(_dereq_,module,exports){
module.exports=_dereq_(32)
},{}],40:[function(_dereq_,module,exports){

/**
 * Module dependencies.
 */

var global = _dereq_('global');

/**
 * Module exports.
 *
 * Logic borrowed from Modernizr:
 *
 *   - https://github.com/Modernizr/Modernizr/blob/master/feature-detects/cors.js
 */

try {
  module.exports = 'XMLHttpRequest' in global &&
    'withCredentials' in new global.XMLHttpRequest();
} catch (err) {
  // if XMLHttp support is disabled in IE then it will throw
  // when trying to create
  module.exports = false;
}

},{"global":41}],41:[function(_dereq_,module,exports){

/**
 * Returns `this`. Execute this without a "context" (i.e. without it being
 * attached to an object of the left-hand side), and `this` points to the
 * "global" scope of the current JS execution.
 */

module.exports = (function () { return this; })();

},{}],42:[function(_dereq_,module,exports){

var indexOf = [].indexOf;

module.exports = function(arr, obj){
  if (indexOf) return arr.indexOf(obj);
  for (var i = 0; i < arr.length; ++i) {
    if (arr[i] === obj) return i;
  }
  return -1;
};
},{}],43:[function(_dereq_,module,exports){

/**
 * HOP ref.
 */

var has = Object.prototype.hasOwnProperty;

/**
 * Return own keys in `obj`.
 *
 * @param {Object} obj
 * @return {Array}
 * @api public
 */

exports.keys = Object.keys || function(obj){
  var keys = [];
  for (var key in obj) {
    if (has.call(obj, key)) {
      keys.push(key);
    }
  }
  return keys;
};

/**
 * Return own values in `obj`.
 *
 * @param {Object} obj
 * @return {Array}
 * @api public
 */

exports.values = function(obj){
  var vals = [];
  for (var key in obj) {
    if (has.call(obj, key)) {
      vals.push(obj[key]);
    }
  }
  return vals;
};

/**
 * Merge `b` into `a`.
 *
 * @param {Object} a
 * @param {Object} b
 * @return {Object} a
 * @api public
 */

exports.merge = function(a, b){
  for (var key in b) {
    if (has.call(b, key)) {
      a[key] = b[key];
    }
  }
  return a;
};

/**
 * Return length of `obj`.
 *
 * @param {Object} obj
 * @return {Number}
 * @api public
 */

exports.length = function(obj){
  return exports.keys(obj).length;
};

/**
 * Check if `obj` is empty.
 *
 * @param {Object} obj
 * @return {Boolean}
 * @api public
 */

exports.isEmpty = function(obj){
  return 0 == exports.length(obj);
};
},{}],44:[function(_dereq_,module,exports){
/**
 * Parses an URI
 *
 * @author Steven Levithan <stevenlevithan.com> (MIT license)
 * @api private
 */

var re = /^(?:(?![^:@]+:[^:@\/]*@)(http|https|ws|wss):\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?((?:[a-f0-9]{0,4}:){2,7}[a-f0-9]{0,4}|[^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/;

var parts = [
    'source', 'protocol', 'authority', 'userInfo', 'user', 'password', 'host'
  , 'port', 'relative', 'path', 'directory', 'file', 'query', 'anchor'
];

module.exports = function parseuri(str) {
  var m = re.exec(str || '')
    , uri = {}
    , i = 14;

  while (i--) {
    uri[parts[i]] = m[i] || '';
  }

  return uri;
};

},{}],45:[function(_dereq_,module,exports){
(function (global){
/*global Blob,File*/

/**
 * Module requirements
 */

var isArray = _dereq_('isarray');
var isBuf = _dereq_('./is-buffer');

/**
 * Replaces every Buffer | ArrayBuffer in packet with a numbered placeholder.
 * Anything with blobs or files should be fed through removeBlobs before coming
 * here.
 *
 * @param {Object} packet - socket.io event packet
 * @return {Object} with deconstructed packet and list of buffers
 * @api public
 */

exports.deconstructPacket = function(packet){
  var buffers = [];
  var packetData = packet.data;

  function _deconstructPacket(data) {
    if (!data) return data;

    if (isBuf(data)) {
      var placeholder = { _placeholder: true, num: buffers.length };
      buffers.push(data);
      return placeholder;
    } else if (isArray(data)) {
      var newData = new Array(data.length);
      for (var i = 0; i < data.length; i++) {
        newData[i] = _deconstructPacket(data[i]);
      }
      return newData;
    } else if ('object' == typeof data && !(data instanceof Date)) {
      var newData = {};
      for (var key in data) {
        newData[key] = _deconstructPacket(data[key]);
      }
      return newData;
    }
    return data;
  }

  var pack = packet;
  pack.data = _deconstructPacket(packetData);
  pack.attachments = buffers.length; // number of binary 'attachments'
  return {packet: pack, buffers: buffers};
};

/**
 * Reconstructs a binary packet from its placeholder packet and buffers
 *
 * @param {Object} packet - event packet with placeholders
 * @param {Array} buffers - binary buffers to put in placeholder positions
 * @return {Object} reconstructed packet
 * @api public
 */

exports.reconstructPacket = function(packet, buffers) {
  var curPlaceHolder = 0;

  function _reconstructPacket(data) {
    if (data && data._placeholder) {
      var buf = buffers[data.num]; // appropriate buffer (should be natural order anyway)
      return buf;
    } else if (isArray(data)) {
      for (var i = 0; i < data.length; i++) {
        data[i] = _reconstructPacket(data[i]);
      }
      return data;
    } else if (data && 'object' == typeof data) {
      for (var key in data) {
        data[key] = _reconstructPacket(data[key]);
      }
      return data;
    }
    return data;
  }

  packet.data = _reconstructPacket(packet.data);
  packet.attachments = undefined; // no longer useful
  return packet;
};

/**
 * Asynchronously removes Blobs or Files from data via
 * FileReader's readAsArrayBuffer method. Used before encoding
 * data as msgpack. Calls callback with the blobless data.
 *
 * @param {Object} data
 * @param {Function} callback
 * @api private
 */

exports.removeBlobs = function(data, callback) {
  function _removeBlobs(obj, curKey, containingObject) {
    if (!obj) return obj;

    // convert any blob
    if ((global.Blob && obj instanceof Blob) ||
        (global.File && obj instanceof File)) {
      pendingBlobs++;

      // async filereader
      var fileReader = new FileReader();
      fileReader.onload = function() { // this.result == arraybuffer
        if (containingObject) {
          containingObject[curKey] = this.result;
        }
        else {
          bloblessData = this.result;
        }

        // if nothing pending its callback time
        if(! --pendingBlobs) {
          callback(bloblessData);
        }
      };

      fileReader.readAsArrayBuffer(obj); // blob -> arraybuffer
    } else if (isArray(obj)) { // handle array
      for (var i = 0; i < obj.length; i++) {
        _removeBlobs(obj[i], i, obj);
      }
    } else if (obj && 'object' == typeof obj && !isBuf(obj)) { // and object
      for (var key in obj) {
        _removeBlobs(obj[key], key, obj);
      }
    }
  }

  var pendingBlobs = 0;
  var bloblessData = data;
  _removeBlobs(bloblessData);
  if (!pendingBlobs) {
    callback(bloblessData);
  }
};

}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"./is-buffer":47,"isarray":48}],46:[function(_dereq_,module,exports){

/**
 * Module dependencies.
 */

var debug = _dereq_('debug')('socket.io-parser');
var json = _dereq_('json3');
var isArray = _dereq_('isarray');
var Emitter = _dereq_('component-emitter');
var binary = _dereq_('./binary');
var isBuf = _dereq_('./is-buffer');

/**
 * Protocol version.
 *
 * @api public
 */

exports.protocol = 4;

/**
 * Packet types.
 *
 * @api public
 */

exports.types = [
  'CONNECT',
  'DISCONNECT',
  'EVENT',
  'BINARY_EVENT',
  'ACK',
  'BINARY_ACK',
  'ERROR'
];

/**
 * Packet type `connect`.
 *
 * @api public
 */

exports.CONNECT = 0;

/**
 * Packet type `disconnect`.
 *
 * @api public
 */

exports.DISCONNECT = 1;

/**
 * Packet type `event`.
 *
 * @api public
 */

exports.EVENT = 2;

/**
 * Packet type `ack`.
 *
 * @api public
 */

exports.ACK = 3;

/**
 * Packet type `error`.
 *
 * @api public
 */

exports.ERROR = 4;

/**
 * Packet type 'binary event'
 *
 * @api public
 */

exports.BINARY_EVENT = 5;

/**
 * Packet type `binary ack`. For acks with binary arguments.
 *
 * @api public
 */

exports.BINARY_ACK = 6;

/**
 * Encoder constructor.
 *
 * @api public
 */

exports.Encoder = Encoder;

/**
 * Decoder constructor.
 *
 * @api public
 */

exports.Decoder = Decoder;

/**
 * A socket.io Encoder instance
 *
 * @api public
 */

function Encoder() {}

/**
 * Encode a packet as a single string if non-binary, or as a
 * buffer sequence, depending on packet type.
 *
 * @param {Object} obj - packet object
 * @param {Function} callback - function to handle encodings (likely engine.write)
 * @return Calls callback with Array of encodings
 * @api public
 */

Encoder.prototype.encode = function(obj, callback){
  debug('encoding packet %j', obj);

  if (exports.BINARY_EVENT == obj.type || exports.BINARY_ACK == obj.type) {
    encodeAsBinary(obj, callback);
  }
  else {
    var encoding = encodeAsString(obj);
    callback([encoding]);
  }
};

/**
 * Encode packet as string.
 *
 * @param {Object} packet
 * @return {String} encoded
 * @api private
 */

function encodeAsString(obj) {
  var str = '';
  var nsp = false;

  // first is type
  str += obj.type;

  // attachments if we have them
  if (exports.BINARY_EVENT == obj.type || exports.BINARY_ACK == obj.type) {
    str += obj.attachments;
    str += '-';
  }

  // if we have a namespace other than `/`
  // we append it followed by a comma `,`
  if (obj.nsp && '/' != obj.nsp) {
    nsp = true;
    str += obj.nsp;
  }

  // immediately followed by the id
  if (null != obj.id) {
    if (nsp) {
      str += ',';
      nsp = false;
    }
    str += obj.id;
  }

  // json data
  if (null != obj.data) {
    if (nsp) str += ',';
    str += json.stringify(obj.data);
  }

  debug('encoded %j as %s', obj, str);
  return str;
}

/**
 * Encode packet as 'buffer sequence' by removing blobs, and
 * deconstructing packet into object with placeholders and
 * a list of buffers.
 *
 * @param {Object} packet
 * @return {Buffer} encoded
 * @api private
 */

function encodeAsBinary(obj, callback) {

  function writeEncoding(bloblessData) {
    var deconstruction = binary.deconstructPacket(bloblessData);
    var pack = encodeAsString(deconstruction.packet);
    var buffers = deconstruction.buffers;

    buffers.unshift(pack); // add packet info to beginning of data list
    callback(buffers); // write all the buffers
  }

  binary.removeBlobs(obj, writeEncoding);
}

/**
 * A socket.io Decoder instance
 *
 * @return {Object} decoder
 * @api public
 */

function Decoder() {
  this.reconstructor = null;
}

/**
 * Mix in `Emitter` with Decoder.
 */

Emitter(Decoder.prototype);

/**
 * Decodes an ecoded packet string into packet JSON.
 *
 * @param {String} obj - encoded packet
 * @return {Object} packet
 * @api public
 */

Decoder.prototype.add = function(obj) {
  var packet;
  if ('string' == typeof obj) {
    packet = decodeString(obj);
    if (exports.BINARY_EVENT == packet.type || exports.BINARY_ACK == packet.type) { // binary packet's json
      this.reconstructor = new BinaryReconstructor(packet);

      // no attachments, labeled binary but no binary data to follow
      if (this.reconstructor.reconPack.attachments === 0) {
        this.emit('decoded', packet);
      }
    } else { // non-binary full packet
      this.emit('decoded', packet);
    }
  }
  else if (isBuf(obj) || obj.base64) { // raw binary data
    if (!this.reconstructor) {
      throw new Error('got binary data when not reconstructing a packet');
    } else {
      packet = this.reconstructor.takeBinaryData(obj);
      if (packet) { // received final buffer
        this.reconstructor = null;
        this.emit('decoded', packet);
      }
    }
  }
  else {
    throw new Error('Unknown type: ' + obj);
  }
};

/**
 * Decode a packet String (JSON data)
 *
 * @param {String} str
 * @return {Object} packet
 * @api private
 */

function decodeString(str) {
  var p = {};
  var i = 0;

  // look up type
  p.type = Number(str.charAt(0));
  if (null == exports.types[p.type]) return error();

  // look up attachments if type binary
  if (exports.BINARY_EVENT == p.type || exports.BINARY_ACK == p.type) {
    var buf = '';
    while (str.charAt(++i) != '-') {
      buf += str.charAt(i);
      if (i == str.length) break;
    }
    if (buf != Number(buf) || str.charAt(i) != '-') {
      throw new Error('Illegal attachments');
    }
    p.attachments = Number(buf);
  }

  // look up namespace (if any)
  if ('/' == str.charAt(i + 1)) {
    p.nsp = '';
    while (++i) {
      var c = str.charAt(i);
      if (',' == c) break;
      p.nsp += c;
      if (i == str.length) break;
    }
  } else {
    p.nsp = 'https://cdn4.tgdd.vn/';
  }

  // look up id
  var next = str.charAt(i + 1);
  if ('' !== next && Number(next) == next) {
    p.id = '';
    while (++i) {
      var c = str.charAt(i);
      if (null == c || Number(c) != c) {
        --i;
        break;
      }
      p.id += str.charAt(i);
      if (i == str.length) break;
    }
    p.id = Number(p.id);
  }

  // look up json data
  if (str.charAt(++i)) {
    try {
      p.data = json.parse(str.substr(i));
    } catch(e){
      return error();
    }
  }

  debug('decoded %s as %j', str, p);
  return p;
}

/**
 * Deallocates a parser's resources
 *
 * @api public
 */

Decoder.prototype.destroy = function() {
  if (this.reconstructor) {
    this.reconstructor.finishedReconstruction();
  }
};

/**
 * A manager of a binary event's 'buffer sequence'. Should
 * be constructed whenever a packet of type BINARY_EVENT is
 * decoded.
 *
 * @param {Object} packet
 * @return {BinaryReconstructor} initialized reconstructor
 * @api private
 */

function BinaryReconstructor(packet) {
  this.reconPack = packet;
  this.buffers = [];
}

/**
 * Method to be called when binary data received from connection
 * after a BINARY_EVENT packet.
 *
 * @param {Buffer | ArrayBuffer} binData - the raw binary data received
 * @return {null | Object} returns null if more binary data is expected or
 *   a reconstructed packet object if all buffers have been received.
 * @api private
 */

BinaryReconstructor.prototype.takeBinaryData = function(binData) {
  this.buffers.push(binData);
  if (this.buffers.length == this.reconPack.attachments) { // done with buffer list
    var packet = binary.reconstructPacket(this.reconPack, this.buffers);
    this.finishedReconstruction();
    return packet;
  }
  return null;
};

/**
 * Cleans up binary packet reconstruction variables.
 *
 * @api private
 */

BinaryReconstructor.prototype.finishedReconstruction = function() {
  this.reconPack = null;
  this.buffers = [];
};

function error(data){
  return {
    type: exports.ERROR,
    data: 'parser error'
  };
}

},{"./binary":45,"./is-buffer":47,"component-emitter":9,"debug":10,"isarray":48,"json3":49}],47:[function(_dereq_,module,exports){
(function (global){

module.exports = isBuf;

/**
 * Returns true if obj is a buffer or an arraybuffer.
 *
 * @api private
 */

function isBuf(obj) {
  return (global.Buffer && global.Buffer.isBuffer(obj)) ||
         (global.ArrayBuffer && obj instanceof ArrayBuffer);
}

}).call(this,typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],48:[function(_dereq_,module,exports){
module.exports=_dereq_(32)
},{}],49:[function(_dereq_,module,exports){
/*! JSON v3.2.6 | http://bestiejs.github.io/json3 | Copyright 2012-2013, Kit Cambridge | http://kit.mit-license.org */
;(function (window) {
  // Convenience aliases.
  var getClass = {}.toString, isProperty, forEach, undef;

  // Detect the `define` function exposed by asynchronous module loaders. The
  // strict `define` check is necessary for compatibility with `r.js`.
  var isLoader = typeof define === "function" && define.amd;

  // Detect native implementations.
  var nativeJSON = typeof JSON == "object" && JSON;

  // Set up the JSON 3 namespace, preferring the CommonJS `exports` object if
  // available.
  var JSON3 = typeof exports == "object" && exports && !exports.nodeType && exports;

  if (JSON3 && nativeJSON) {
    // Explicitly delegate to the native `stringify` and `parse`
    // implementations in CommonJS environments.
    JSON3.stringify = nativeJSON.stringify;
    JSON3.parse = nativeJSON.parse;
  } else {
    // Export for web browsers, JavaScript engines, and asynchronous module
    // loaders, using the global `JSON` object if available.
    JSON3 = window.JSON = nativeJSON || {};
  }

  // Test the `Date#getUTC*` methods. Based on work by @Yaffle.
  var isExtended = new Date(-3509827334573292);
  try {
    // The `getUTCFullYear`, `Month`, and `Date` methods return nonsensical
    // results for certain dates in Opera >= 10.53.
    isExtended = isExtended.getUTCFullYear() == -109252 && isExtended.getUTCMonth() === 0 && isExtended.getUTCDate() === 1 &&
      // Safari < 2.0.2 stores the internal millisecond time value correctly,
      // but clips the values returned by the date methods to the range of
      // signed 32-bit integers ([-2 ** 31, 2 ** 31 - 1]).
      isExtended.getUTCHours() == 10 && isExtended.getUTCMinutes() == 37 && isExtended.getUTCSeconds() == 6 && isExtended.getUTCMilliseconds() == 708;
  } catch (exception) {}

  // Internal: Determines whether the native `JSON.stringify` and `parse`
  // implementations are spec-compliant. Based on work by Ken Snyder.
  function has(name) {
    if (has[name] !== undef) {
      // Return cached feature test result.
      return has[name];
    }

    var isSupported;
    if (name == "bug-string-char-index") {
      // IE <= 7 doesn't support accessing string characters using square
      // bracket notation. IE 8 only supports this for primitives.
      isSupported = "a"[0] != "a";
    } else if (name == "json") {
      // Indicates whether both `JSON.stringify` and `JSON.parse` are
      // supported.
      isSupported = has("json-stringify") && has("json-parse");
    } else {
      var value, serialized = '{"a":[1,true,false,null,"\\u0000\\b\\n\\f\\r\\t"]}';
      // Test `JSON.stringify`.
      if (name == "json-stringify") {
        var stringify = JSON3.stringify, stringifySupported = typeof stringify == "function" && isExtended;
        if (stringifySupported) {
          // A test function object with a custom `toJSON` method.
          (value = function () {
            return 1;
          }).toJSON = value;
          try {
            stringifySupported =
              // Firefox 3.1b1 and b2 serialize string, number, and boolean
              // primitives as object literals.
              stringify(0) === "0" &&
              // FF 3.1b1, b2, and JSON 2 serialize wrapped primitives as object
              // literals.
              stringify(new Number()) === "0" &&
              stringify(new String()) == '""' &&
              // FF 3.1b1, 2 throw an error if the value is `null`, `undefined`, or
              // does not define a canonical JSON representation (this applies to
              // objects with `toJSON` properties as well, *unless* they are nested
              // within an object or array).
              stringify(getClass) === undef &&
              // IE 8 serializes `undefined` as `"undefined"`. Safari <= 5.1.7 and
              // FF 3.1b3 pass this test.
              stringify(undef) === undef &&
              // Safari <= 5.1.7 and FF 3.1b3 throw `Error`s and `TypeError`s,
              // respectively, if the value is omitted entirely.
              stringify() === undef &&
              // FF 3.1b1, 2 throw an error if the given value is not a number,
              // string, array, object, Boolean, or `null` literal. This applies to
              // objects with custom `toJSON` methods as well, unless they are nested
              // inside object or array literals. YUI 3.0.0b1 ignores custom `toJSON`
              // methods entirely.
              stringify(value) === "1" &&
              stringify([value]) == "[1]" &&
              // Prototype <= 1.6.1 serializes `[undefined]` as `"[]"` instead of
              // `"[null]"`.
              stringify([undef]) == "[null]" &&
              // YUI 3.0.0b1 fails to serialize `null` literals.
              stringify(null) == "null" &&
              // FF 3.1b1, 2 halts serialization if an array contains a function:
              // `[1, true, getClass, 1]` serializes as "[1,true,],". FF 3.1b3
              // elides non-JSON values from objects and arrays, unless they
              // define custom `toJSON` methods.
              stringify([undef, getClass, null]) == "[null,null,null]" &&
              // Simple serialization test. FF 3.1b1 uses Unicode escape sequences
              // where character escape codes are expected (e.g., `\b` => `\u0008`).
              stringify({ "a": [value, true, false, null, "\x00\b\n\f\r\t"] }) == serialized &&
              // FF 3.1b1 and b2 ignore the `filter` and `width` arguments.
              stringify(null, value) === "1" &&
              stringify([1, 2], null, 1) == "[\n 1,\n 2\n]" &&
              // JSON 2, Prototype <= 1.7, and older WebKit builds incorrectly
              // serialize extended years.
              stringify(new Date(-8.64e15)) == '"-271821-04-20T00:00:00.000Z"' &&
              // The milliseconds are optional in ES 5, but required in 5.1.
              stringify(new Date(8.64e15)) == '"+275760-09-13T00:00:00.000Z"' &&
              // Firefox <= 11.0 incorrectly serializes years prior to 0 as negative
              // four-digit years instead of six-digit years. Credits: @Yaffle.
              stringify(new Date(-621987552e5)) == '"-000001-01-01T00:00:00.000Z"' &&
              // Safari <= 5.1.5 and Opera >= 10.53 incorrectly serialize millisecond
              // values less than 1000. Credits: @Yaffle.
              stringify(new Date(-1)) == '"1969-12-31T23:59:59.999Z"';
          } catch (exception) {
            stringifySupported = false;
          }
        }
        isSupported = stringifySupported;
      }
      // Test `JSON.parse`.
      if (name == "json-parse") {
        var parse = JSON3.parse;
        if (typeof parse == "function") {
          try {
            // FF 3.1b1, b2 will throw an exception if a bare literal is provided.
            // Conforming implementations should also coerce the initial argument to
            // a string prior to parsing.
            if (parse("0") === 0 && !parse(false)) {
              // Simple parsing test.
              value = parse(serialized);
              var parseSupported = value["a"].length == 5 && value["a"][0] === 1;
              if (parseSupported) {
                try {
                  // Safari <= 5.1.2 and FF 3.1b1 allow unescaped tabs in strings.
                  parseSupported = !parse('"\t"');
                } catch (exception) {}
                if (parseSupported) {
                  try {
                    // FF 4.0 and 4.0.1 allow leading `+` signs and leading
                    // decimal points. FF 4.0, 4.0.1, and IE 9-10 also allow
                    // certain octal literals.
                    parseSupported = parse("01") !== 1;
                  } catch (exception) {}
                }
                if (parseSupported) {
                  try {
                    // FF 4.0, 4.0.1, and Rhino 1.7R3-R4 allow trailing decimal
                    // points. These environments, along with FF 3.1b1 and 2,
                    // also allow trailing commas in JSON objects and arrays.
                    parseSupported = parse("1.") !== 1;
                  } catch (exception) {}
                }
              }
            }
          } catch (exception) {
            parseSupported = false;
          }
        }
        isSupported = parseSupported;
      }
    }
    return has[name] = !!isSupported;
  }

  if (!has("json")) {
    // Common `[[Class]]` name aliases.
    var functionClass = "[object Function]";
    var dateClass = "[object Date]";
    var numberClass = "[object Number]";
    var stringClass = "[object String]";
    var arrayClass = "[object Array]";
    var booleanClass = "[object Boolean]";

    // Detect incomplete support for accessing string characters by index.
    var charIndexBuggy = has("bug-string-char-index");

    // Define additional utility methods if the `Date` methods are buggy.
    if (!isExtended) {
      var floor = Math.floor;
      // A mapping between the months of the year and the number of days between
      // January 1st and the first of the respective month.
      var Months = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
      // Internal: Calculates the number of days between the Unix epoch and the
      // first day of the given month.
      var getDay = function (year, month) {
        return Months[month] + 365 * (year - 1970) + floor((year - 1969 + (month = +(month > 1))) / 4) - floor((year - 1901 + month) / 100) + floor((year - 1601 + month) / 400);
      };
    }

    // Internal: Determines if a property is a direct property of the given
    // object. Delegates to the native `Object#hasOwnProperty` method.
    if (!(isProperty = {}.hasOwnProperty)) {
      isProperty = function (property) {
        var members = {}, constructor;
        if ((members.__proto__ = null, members.__proto__ = {
          // The *proto* property cannot be set multiple times in recent
          // versions of Firefox and SeaMonkey.
          "toString": 1
        }, members).toString != getClass) {
          // Safari <= 2.0.3 doesn't implement `Object#hasOwnProperty`, but
          // supports the mutable *proto* property.
          isProperty = function (property) {
            // Capture and break the object's prototype chain (see section 8.6.2
            // of the ES 5.1 spec). The parenthesized expression prevents an
            // unsafe transformation by the Closure Compiler.
            var original = this.__proto__, result = property in (this.__proto__ = null, this);
            // Restore the original prototype chain.
            this.__proto__ = original;
            return result;
          };
        } else {
          // Capture a reference to the top-level `Object` constructor.
          constructor = members.constructor;
          // Use the `constructor` property to simulate `Object#hasOwnProperty` in
          // other environments.
          isProperty = function (property) {
            var parent = (this.constructor || constructor).prototype;
            return property in this && !(property in parent && this[property] === parent[property]);
          };
        }
        members = null;
        return isProperty.call(this, property);
      };
    }

    // Internal: A set of primitive types used by `isHostType`.
    var PrimitiveTypes = {
      'boolean': 1,
      'number': 1,
      'string': 1,
      'undefined': 1
    };

    // Internal: Determines if the given object `property` value is a
    // non-primitive.
    var isHostType = function (object, property) {
      var type = typeof object[property];
      return type == 'object' ? !!object[property] : !PrimitiveTypes[type];
    };

    // Internal: Normalizes the `for...in` iteration algorithm across
    // environments. Each enumerated key is yielded to a `callback` function.
    forEach = function (object, callback) {
      var size = 0, Properties, members, property;

      // Tests for bugs in the current environment's `for...in` algorithm. The
      // `valueOf` property inherits the non-enumerable flag from
      // `Object.prototype` in older versions of IE, Netscape, and Mozilla.
      (Properties = function () {
        this.valueOf = 0;
      }).prototype.valueOf = 0;

      // Iterate over a new instance of the `Properties` class.
      members = new Properties();
      for (property in members) {
        // Ignore all properties inherited from `Object.prototype`.
        if (isProperty.call(members, property)) {
          size++;
        }
      }
      Properties = members = null;

      // Normalize the iteration algorithm.
      if (!size) {
        // A list of non-enumerable properties inherited from `Object.prototype`.
        members = ["valueOf", "toString", "toLocaleString", "propertyIsEnumerable", "isPrototypeOf", "hasOwnProperty", "constructor"];
        // IE <= 8, Mozilla 1.0, and Netscape 6.2 ignore shadowed non-enumerable
        // properties.
        forEach = function (object, callback) {
          var isFunction = getClass.call(object) == functionClass, property, length;
          var hasProperty = !isFunction && typeof object.constructor != 'function' && isHostType(object, 'hasOwnProperty') ? object.hasOwnProperty : isProperty;
          for (property in object) {
            // Gecko <= 1.0 enumerates the `prototype` property of functions under
            // certain conditions; IE does not.
            if (!(isFunction && property == "prototype") && hasProperty.call(object, property)) {
              callback(property);
            }
          }
          // Manually invoke the callback for each non-enumerable property.
          for (length = members.length; property = members[--length]; hasProperty.call(object, property) && callback(property));
        };
      } else if (size == 2) {
        // Safari <= 2.0.4 enumerates shadowed properties twice.
        forEach = function (object, callback) {
          // Create a set of iterated properties.
          var members = {}, isFunction = getClass.call(object) == functionClass, property;
          for (property in object) {
            // Store each property name to prevent double enumeration. The
            // `prototype` property of functions is not enumerated due to cross-
            // environment inconsistencies.
            if (!(isFunction && property == "prototype") && !isProperty.call(members, property) && (members[property] = 1) && isProperty.call(object, property)) {
              callback(property);
            }
          }
        };
      } else {
        // No bugs detected; use the standard `for...in` algorithm.
        forEach = function (object, callback) {
          var isFunction = getClass.call(object) == functionClass, property, isConstructor;
          for (property in object) {
            if (!(isFunction && property == "prototype") && isProperty.call(object, property) && !(isConstructor = property === "constructor")) {
              callback(property);
            }
          }
          // Manually invoke the callback for the `constructor` property due to
          // cross-environment inconsistencies.
          if (isConstructor || isProperty.call(object, (property = "constructor"))) {
            callback(property);
          }
        };
      }
      return forEach(object, callback);
    };

    // Public: Serializes a JavaScript `value` as a JSON string. The optional
    // `filter` argument may specify either a function that alters how object and
    // array members are serialized, or an array of strings and numbers that
    // indicates which properties should be serialized. The optional `width`
    // argument may be either a string or number that specifies the indentation
    // level of the output.
    if (!has("json-stringify")) {
      // Internal: A map of control characters and their escaped equivalents.
      var Escapes = {
        92: "\\\\",
        34: '\\"',
        8: "\\b",
        12: "\\f",
        10: "\\n",
        13: "\\r",
        9: "\\t"
      };

      // Internal: Converts `value` into a zero-padded string such that its
      // length is at least equal to `width`. The `width` must be <= 6.
      var leadingZeroes = "000000";
      var toPaddedString = function (width, value) {
        // The `|| 0` expression is necessary to work around a bug in
        // Opera <= 7.54u2 where `0 == -0`, but `String(-0) !== "0"`.
        return (leadingZeroes + (value || 0)).slice(-width);
      };

      // Internal: Double-quotes a string `value`, replacing all ASCII control
      // characters (characters with code unit values between 0 and 31) with
      // their escaped equivalents. This is an implementation of the
      // `Quote(value)` operation defined in ES 5.1 section 15.12.3.
      var unicodePrefix = "\\u00";
      var quote = function (value) {
        var result = '"', index = 0, length = value.length, isLarge = length > 10 && charIndexBuggy, symbols;
        if (isLarge) {
          symbols = value.split("");
        }
        for (; index < length; index++) {
          var charCode = value.charCodeAt(index);
          // If the character is a control character, append its Unicode or
          // shorthand escape sequence; otherwise, append the character as-is.
          switch (charCode) {
            case 8: case 9: case 10: case 12: case 13: case 34: case 92:
              result += Escapes[charCode];
              break;
            default:
              if (charCode < 32) {
                result += unicodePrefix + toPaddedString(2, charCode.toString(16));
                break;
              }
              result += isLarge ? symbols[index] : charIndexBuggy ? value.charAt(index) : value[index];
          }
        }
        return result + '"';
      };

      // Internal: Recursively serializes an object. Implements the
      // `Str(key, holder)`, `JO(value)`, and `JA(value)` operations.
      var serialize = function (property, object, callback, properties, whitespace, indentation, stack) {
        var value, className, year, month, date, time, hours, minutes, seconds, milliseconds, results, element, index, length, prefix, result;
        try {
          // Necessary for host object support.
          value = object[property];
        } catch (exception) {}
        if (typeof value == "object" && value) {
          className = getClass.call(value);
          if (className == dateClass && !isProperty.call(value, "toJSON")) {
            if (value > -1 / 0 && value < 1 / 0) {
              // Dates are serialized according to the `Date#toJSON` method
              // specified in ES 5.1 section 15.9.5.44. See section 15.9.1.15
              // for the ISO 8601 date time string format.
              if (getDay) {
                // Manually compute the year, month, date, hours, minutes,
                // seconds, and milliseconds if the `getUTC*` methods are
                // buggy. Adapted from @Yaffle's `date-shim` project.
                date = floor(value / 864e5);
                for (year = floor(date / 365.2425) + 1970 - 1; getDay(year + 1, 0) <= date; year++);
                for (month = floor((date - getDay(year, 0)) / 30.42); getDay(year, month + 1) <= date; month++);
                date = 1 + date - getDay(year, month);
                // The `time` value specifies the time within the day (see ES
                // 5.1 section 15.9.1.2). The formula `(A % B + B) % B` is used
                // to compute `A modulo B`, as the `%` operator does not
                // correspond to the `modulo` operation for negative numbers.
                time = (value % 864e5 + 864e5) % 864e5;
                // The hours, minutes, seconds, and milliseconds are obtained by
                // decomposing the time within the day. See section 15.9.1.10.
                hours = floor(time / 36e5) % 24;
                minutes = floor(time / 6e4) % 60;
                seconds = floor(time / 1e3) % 60;
                milliseconds = time % 1e3;
              } else {
                year = value.getUTCFullYear();
                month = value.getUTCMonth();
                date = value.getUTCDate();
                hours = value.getUTCHours();
                minutes = value.getUTCMinutes();
                seconds = value.getUTCSeconds();
                milliseconds = value.getUTCMilliseconds();
              }
              // Serialize extended years correctly.
              value = (year <= 0 || year >= 1e4 ? (year < 0 ? "-" : "+") + toPaddedString(6, year < 0 ? -year : year) : toPaddedString(4, year)) +
                "-" + toPaddedString(2, month + 1) + "-" + toPaddedString(2, date) +
                // Months, dates, hours, minutes, and seconds should have two
                // digits; milliseconds should have three.
                "T" + toPaddedString(2, hours) + ":" + toPaddedString(2, minutes) + ":" + toPaddedString(2, seconds) +
                // Milliseconds are optional in ES 5.0, but required in 5.1.
                "." + toPaddedString(3, milliseconds) + "Z";
            } else {
              value = null;
            }
          } else if (typeof value.toJSON == "function" && ((className != numberClass && className != stringClass && className != arrayClass) || isProperty.call(value, "toJSON"))) {
            // Prototype <= 1.6.1 adds non-standard `toJSON` methods to the
            // `Number`, `String`, `Date`, and `Array` prototypes. JSON 3
            // ignores all `toJSON` methods on these objects unless they are
            // defined directly on an instance.
            value = value.toJSON(property);
          }
        }
        if (callback) {
          // If a replacement function was provided, call it to obtain the value
          // for serialization.
          value = callback.call(object, property, value);
        }
        if (value === null) {
          return "null";
        }
        className = getClass.call(value);
        if (className == booleanClass) {
          // Booleans are represented literally.
          return "" + value;
        } else if (className == numberClass) {
          // JSON numbers must be finite. `Infinity` and `NaN` are serialized as
          // `"null"`.
          return value > -1 / 0 && value < 1 / 0 ? "" + value : "null";
        } else if (className == stringClass) {
          // Strings are double-quoted and escaped.
          return quote("" + value);
        }
        // Recursively serialize objects and arrays.
        if (typeof value == "object") {
          // Check for cyclic structures. This is a linear search; performance
          // is inversely proportional to the number of unique nested objects.
          for (length = stack.length; length--;) {
            if (stack[length] === value) {
              // Cyclic structures cannot be serialized by `JSON.stringify`.
              throw TypeError();
            }
          }
          // Add the object to the stack of traversed objects.
          stack.push(value);
          results = [];
          // Save the current indentation level and indent one additional level.
          prefix = indentation;
          indentation += whitespace;
          if (className == arrayClass) {
            // Recursively serialize array elements.
            for (index = 0, length = value.length; index < length; index++) {
              element = serialize(index, value, callback, properties, whitespace, indentation, stack);
              results.push(element === undef ? "null" : element);
            }
            result = results.length ? (whitespace ? "[\n" + indentation + results.join(",\n" + indentation) + "\n" + prefix + "]" : ("[" + results.join(",") + "]")) : "[]";
          } else {
            // Recursively serialize object members. Members are selected from
            // either a user-specified list of property names, or the object
            // itself.
            forEach(properties || value, function (property) {
              var element = serialize(property, value, callback, properties, whitespace, indentation, stack);
              if (element !== undef) {
                // According to ES 5.1 section 15.12.3: "If `gap` {whitespace}
                // is not the empty string, let `member` {quote(property) + ":"}
                // be the concatenation of `member` and the `space` character."
                // The "`space` character" refers to the literal space
                // character, not the `space` {width} argument provided to
                // `JSON.stringify`.
                results.push(quote(property) + ":" + (whitespace ? " " : "") + element);
              }
            });
            result = results.length ? (whitespace ? "{\n" + indentation + results.join(",\n" + indentation) + "\n" + prefix + "}" : ("{" + results.join(",") + "}")) : "{}";
          }
          // Remove the object from the traversed object stack.
          stack.pop();
          return result;
        }
      };

      // Public: `JSON.stringify`. See ES 5.1 section 15.12.3.
      JSON3.stringify = function (source, filter, width) {
        var whitespace, callback, properties, className;
        if (typeof filter == "function" || typeof filter == "object" && filter) {
          if ((className = getClass.call(filter)) == functionClass) {
            callback = filter;
          } else if (className == arrayClass) {
            // Convert the property names array into a makeshift set.
            properties = {};
            for (var index = 0, length = filter.length, value; index < length; value = filter[index++], ((className = getClass.call(value)), className == stringClass || className == numberClass) && (properties[value] = 1));
          }
        }
        if (width) {
          if ((className = getClass.call(width)) == numberClass) {
            // Convert the `width` to an integer and create a string containing
            // `width` number of space characters.
            if ((width -= width % 1) > 0) {
              for (whitespace = "", width > 10 && (width = 10); whitespace.length < width; whitespace += " ");
            }
          } else if (className == stringClass) {
            whitespace = width.length <= 10 ? width : width.slice(0, 10);
          }
        }
        // Opera <= 7.54u2 discards the values associated with empty string keys
        // (`""`) only if they are used directly within an object member list
        // (e.g., `!("" in { "": 1})`).
        return serialize("", (value = {}, value[""] = source, value), callback, properties, whitespace, "", []);
      };
    }

    // Public: Parses a JSON source string.
    if (!has("json-parse")) {
      var fromCharCode = String.fromCharCode;

      // Internal: A map of escaped control characters and their unescaped
      // equivalents.
      var Unescapes = {
        92: "\\",
        34: '"',
        47: "/",
        98: "\b",
        116: "\t",
        110: "\n",
        102: "\f",
        114: "\r"
      };

      // Internal: Stores the parser state.
      var Index, Source;

      // Internal: Resets the parser state and throws a `SyntaxError`.
      var abort = function() {
        Index = Source = null;
        throw SyntaxError();
      };

      // Internal: Returns the next token, or `"$"` if the parser has reached
      // the end of the source string. A token may be a string, number, `null`
      // literal, or Boolean literal.
      var lex = function () {
        var source = Source, length = source.length, value, begin, position, isSigned, charCode;
        while (Index < length) {
          charCode = source.charCodeAt(Index);
          switch (charCode) {
            case 9: case 10: case 13: case 32:
              // Skip whitespace tokens, including tabs, carriage returns, line
              // feeds, and space characters.
              Index++;
              break;
            case 123: case 125: case 91: case 93: case 58: case 44:
              // Parse a punctuator token (`{`, `}`, `[`, `]`, `:`, or `,`) at
              // the current position.
              value = charIndexBuggy ? source.charAt(Index) : source[Index];
              Index++;
              return value;
            case 34:
              // `"` delimits a JSON string; advance to the next character and
              // begin parsing the string. String tokens are prefixed with the
              // sentinel `@` character to distinguish them from punctuators and
              // end-of-string tokens.
              for (value = "@", Index++; Index < length;) {
                charCode = source.charCodeAt(Index);
                if (charCode < 32) {
                  // Unescaped ASCII control characters (those with a code unit
                  // less than the space character) are not permitted.
                  abort();
                } else if (charCode == 92) {
                  // A reverse solidus (`\`) marks the beginning of an escaped
                  // control character (including `"`, `\`, and `/`) or Unicode
                  // escape sequence.
                  charCode = source.charCodeAt(++Index);
                  switch (charCode) {
                    case 92: case 34: case 47: case 98: case 116: case 110: case 102: case 114:
                      // Revive escaped control characters.
                      value += Unescapes[charCode];
                      Index++;
                      break;
                    case 117:
                      // `\u` marks the beginning of a Unicode escape sequence.
                      // Advance to the first character and validate the
                      // four-digit code point.
                      begin = ++Index;
                      for (position = Index + 4; Index < position; Index++) {
                        charCode = source.charCodeAt(Index);
                        // A valid sequence comprises four hexdigits (case-
                        // insensitive) that form a single hexadecimal value.
                        if (!(charCode >= 48 && charCode <= 57 || charCode >= 97 && charCode <= 102 || charCode >= 65 && charCode <= 70)) {
                          // Invalid Unicode escape sequence.
                          abort();
                        }
                      }
                      // Revive the escaped character.
                      value += fromCharCode("0x" + source.slice(begin, Index));
                      break;
                    default:
                      // Invalid escape sequence.
                      abort();
                  }
                } else {
                  if (charCode == 34) {
                    // An unescaped double-quote character marks the end of the
                    // string.
                    break;
                  }
                  charCode = source.charCodeAt(Index);
                  begin = Index;
                  // Optimize for the common case where a string is valid.
                  while (charCode >= 32 && charCode != 92 && charCode != 34) {
                    charCode = source.charCodeAt(++Index);
                  }
                  // Append the string as-is.
                  value += source.slice(begin, Index);
                }
              }
              if (source.charCodeAt(Index) == 34) {
                // Advance to the next character and return the revived string.
                Index++;
                return value;
              }
              // Unterminated string.
              abort();
            default:
              // Parse numbers and literals.
              begin = Index;
              // Advance past the negative sign, if one is specified.
              if (charCode == 45) {
                isSigned = true;
                charCode = source.charCodeAt(++Index);
              }
              // Parse an integer or floating-point value.
              if (charCode >= 48 && charCode <= 57) {
                // Leading zeroes are interpreted as octal literals.
                if (charCode == 48 && ((charCode = source.charCodeAt(Index + 1)), charCode >= 48 && charCode <= 57)) {
                  // Illegal octal literal.
                  abort();
                }
                isSigned = false;
                // Parse the integer component.
                for (; Index < length && ((charCode = source.charCodeAt(Index)), charCode >= 48 && charCode <= 57); Index++);
                // Floats cannot contain a leading decimal point; however, this
                // case is already accounted for by the parser.
                if (source.charCodeAt(Index) == 46) {
                  position = ++Index;
                  // Parse the decimal component.
                  for (; position < length && ((charCode = source.charCodeAt(position)), charCode >= 48 && charCode <= 57); position++);
                  if (position == Index) {
                    // Illegal trailing decimal.
                    abort();
                  }
                  Index = position;
                }
                // Parse exponents. The `e` denoting the exponent is
                // case-insensitive.
                charCode = source.charCodeAt(Index);
                if (charCode == 101 || charCode == 69) {
                  charCode = source.charCodeAt(++Index);
                  // Skip past the sign following the exponent, if one is
                  // specified.
                  if (charCode == 43 || charCode == 45) {
                    Index++;
                  }
                  // Parse the exponential component.
                  for (position = Index; position < length && ((charCode = source.charCodeAt(position)), charCode >= 48 && charCode <= 57); position++);
                  if (position == Index) {
                    // Illegal empty exponent.
                    abort();
                  }
                  Index = position;
                }
                // Coerce the parsed value to a JavaScript number.
                return +source.slice(begin, Index);
              }
              // A negative sign may only precede numbers.
              if (isSigned) {
                abort();
              }
              // `true`, `false`, and `null` literals.
              if (source.slice(Index, Index + 4) == "true") {
                Index += 4;
                return true;
              } else if (source.slice(Index, Index + 5) == "false") {
                Index += 5;
                return false;
              } else if (source.slice(Index, Index + 4) == "null") {
                Index += 4;
                return null;
              }
              // Unrecognized token.
              abort();
          }
        }
        // Return the sentinel `$` character if the parser has reached the end
        // of the source string.
        return "$";
      };

      // Internal: Parses a JSON `value` token.
      var get = function (value) {
        var results, hasMembers;
        if (value == "$") {
          // Unexpected end of input.
          abort();
        }
        if (typeof value == "string") {
          if ((charIndexBuggy ? value.charAt(0) : value[0]) == "@") {
            // Remove the sentinel `@` character.
            return value.slice(1);
          }
          // Parse object and array literals.
          if (value == "[") {
            // Parses a JSON array, returning a new JavaScript array.
            results = [];
            for (;; hasMembers || (hasMembers = true)) {
              value = lex();
              // A closing square bracket marks the end of the array literal.
              if (value == "]") {
                break;
              }
              // If the array literal contains elements, the current token
              // should be a comma separating the previous element from the
              // next.
              if (hasMembers) {
                if (value == ",") {
                  value = lex();
                  if (value == "]") {
                    // Unexpected trailing `,` in array literal.
                    abort();
                  }
                } else {
                  // A `,` must separate each array element.
                  abort();
                }
              }
              // Elisions and leading commas are not permitted.
              if (value == ",") {
                abort();
              }
              results.push(get(value));
            }
            return results;
          } else if (value == "{") {
            // Parses a JSON object, returning a new JavaScript object.
            results = {};
            for (;; hasMembers || (hasMembers = true)) {
              value = lex();
              // A closing curly brace marks the end of the object literal.
              if (value == "}") {
                break;
              }
              // If the object literal contains members, the current token
              // should be a comma separator.
              if (hasMembers) {
                if (value == ",") {
                  value = lex();
                  if (value == "}") {
                    // Unexpected trailing `,` in object literal.
                    abort();
                  }
                } else {
                  // A `,` must separate each object member.
                  abort();
                }
              }
              // Leading commas are not permitted, object property names must be
              // double-quoted strings, and a `:` must separate each property
              // name and value.
              if (value == "," || typeof value != "string" || (charIndexBuggy ? value.charAt(0) : value[0]) != "@" || lex() != ":") {
                abort();
              }
              results[value.slice(1)] = get(lex());
            }
            return results;
          }
          // Unexpected token encountered.
          abort();
        }
        return value;
      };

      // Internal: Updates a traversed object member.
      var update = function(source, property, callback) {
        var element = walk(source, property, callback);
        if (element === undef) {
          delete source[property];
        } else {
          source[property] = element;
        }
      };

      // Internal: Recursively traverses a parsed JSON object, invoking the
      // `callback` function for each value. This is an implementation of the
      // `Walk(holder, name)` operation defined in ES 5.1 section 15.12.2.
      var walk = function (source, property, callback) {
        var value = source[property], length;
        if (typeof value == "object" && value) {
          // `forEach` can't be used to traverse an array in Opera <= 8.54
          // because its `Object#hasOwnProperty` implementation returns `false`
          // for array indices (e.g., `![1, 2, 3].hasOwnProperty("0")`).
          if (getClass.call(value) == arrayClass) {
            for (length = value.length; length--;) {
              update(value, length, callback);
            }
          } else {
            forEach(value, function (property) {
              update(value, property, callback);
            });
          }
        }
        return callback.call(source, property, value);
      };

      // Public: `JSON.parse`. See ES 5.1 section 15.12.2.
      JSON3.parse = function (source, callback) {
        var result, value;
        Index = 0;
        Source = "" + source;
        result = get(lex());
        // If a JSON string contains multiple tokens, it is invalid.
        if (lex() != "$") {
          abort();
        }
        // Reset the parser state.
        Index = Source = null;
        return callback && getClass.call(callback) == functionClass ? walk((value = {}, value[""] = result, value), "", callback) : result;
      };
    }
  }

  // Export for asynchronous module loaders.
  if (isLoader) {
    define(function () {
      return JSON3;
    });
  }
}(this));

},{}],50:[function(_dereq_,module,exports){
module.exports = toArray

function toArray(list, index) {
    var array = []

    index = index || 0

    for (var i = index || 0; i < list.length; i++) {
        array[i - index] = list[i]
    }

    return array
}

},{}]},{},[1])
(1)
});
Date.CultureInfo = { name: "vi-VN", englishName: "Vietnamese (Vietnam)", nativeName: "Tiếng Việt (Việt Nam)", dayNames: ["Chủ Nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"], abbreviatedDayNames: ["CN", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy"], shortestDayNames: ["C", "H", "B", "T", "N", "S", "B"], firstLetterDayNames: ["C", "H", "B", "T", "N", "S", "B"], monthNames: ["Tháng Giêng", "Tháng Hai", "Tháng Ba", "Tháng Tư", "Tháng Năm", "Tháng Sáu", "Tháng Bảy", "Tháng Tám", "Tháng Chín", "Tháng Mười", "Tháng Mười Một", "Tháng Mười Hai"], abbreviatedMonthNames: ["Thg1", "Thg2", "Thg3", "Thg4", "Thg5", "Thg6", "Thg7", "Thg8", "Thg9", "Thg10", "Thg11", "Thg12"], amDesignator: "SA", pmDesignator: "CH", firstDayOfWeek: 1, twoDigitYearMax: 2029, dateElementOrder: "dmy", formatPatterns: { shortDate: "dd/MM/yyyy", longDate: "dd MMMM yyyy", shortTime: "h:mm tt", longTime: "h:mm:ss tt", fullDateTime: "dd MMMM yyyy h:mm:ss tt", sortableDateTime: "yyyy-MM-ddTHH:mm:ss", universalSortableDateTime: "yyyy-MM-dd HH:mm:ssZ", rfc1123: "ddd, dd MMM yyyy HH:mm:ss GMT", monthDay: "dd MMMM", yearMonth: "MMMM yyyy" }, regexPatterns: { jan: /^tháng giêng/i, feb: /^tháng hai/i, mar: /^tháng ba/i, apr: /^tháng tư/i, may: /^tháng năm/i, jun: /^tháng sáu/i, jul: /^tháng bảy/i, aug: /^tháng tám/i, sep: /^tháng chín/i, oct: /^tháng mười/i, nov: /^tháng mười một/i, dec: /^tháng mười hai/i, sun: /^c(n(ủ nhật)?)?/i, mon: /^h(ai(́ hai)?)?/i, tue: /^b(a(ứ ba)?)?/i, wed: /^t(ư(ứ tư)?)?/i, thu: /^n(ăm(́ năm)?)?/i, fri: /^s(áu( sáu)?)?/i, sat: /^b(ảy( bảy)?)?/i, future: /^next/i, past: /^last|past|prev(ious)?/i, add: /^(\+|aft(er)?|from|hence)/i, subtract: /^(\-|bef(ore)?|ago)/i, yesterday: /^yes(terday)?/i, today: /^t(od(ay)?)?/i, tomorrow: /^tom(orrow)?/i, now: /^n(ow)?/i, millisecond: /^ms|milli(second)?s?/i, second: /^sec(ond)?s?/i, minute: /^mn|min(ute)?s?/i, hour: /^h(our)?s?/i, week: /^w(eek)?s?/i, month: /^m(onth)?s?/i, day: /^d(ay)?s?/i, year: /^y(ear)?s?/i, shortMeridian: /^(a|p)/i, longMeridian: /^(a\.?m?\.?|p\.?m?\.?)/i, timezone: /^((e(s|d)t|c(s|d)t|m(s|d)t|p(s|d)t)|((gmt)?\s*(\+|\-)\s*\d\d\d\d?)|gmt|utc)/i, ordinalSuffix: /^\s*(st|nd|rd|th)/i, timeContext: /^\s*(\:|a(?!u|p)|p)/i }, timezones: [{ name: "UTC", offset: "-000" }, { name: "GMT", offset: "-000" }, { name: "EST", offset: "-0500" }, { name: "EDT", offset: "-0400" }, { name: "CST", offset: "-0600" }, { name: "CDT", offset: "-0500" }, { name: "MST", offset: "-0700" }, { name: "MDT", offset: "-0600" }, { name: "PST", offset: "-0800" }, { name: "PDT", offset: "-0700" }] };
(function () {
    var $D = Date, $P = $D.prototype, $C = $D.CultureInfo, p = function (s, l) {
        if (!l) { l = 2; }
        return ("000" + s).slice(l * -1);
    }; $P.clearTime = function () { this.setHours(0); this.setMinutes(0); this.setSeconds(0); this.setMilliseconds(0); return this; }; $P.setTimeToNow = function () { var n = new Date(); this.setHours(n.getHours()); this.setMinutes(n.getMinutes()); this.setSeconds(n.getSeconds()); this.setMilliseconds(n.getMilliseconds()); return this; }; $D.today = function () { return new Date().clearTime(); }; $D.compare = function (date1, date2) { if (isNaN(date1) || isNaN(date2)) { throw new Error(date1 + " - " + date2); } else if (date1 instanceof Date && date2 instanceof Date) { return (date1 < date2) ? -1 : (date1 > date2) ? 1 : 0; } else { throw new TypeError(date1 + " - " + date2); } }; $D.equals = function (date1, date2) { return (date1.compareTo(date2) === 0); }; $D.getDayNumberFromName = function (name) {
        var n = $C.dayNames, m = $C.abbreviatedDayNames, o = $C.shortestDayNames, s = name.toLowerCase(); for (var i = 0; i < n.length; i++) { if (n[i].toLowerCase() == s || m[i].toLowerCase() == s || o[i].toLowerCase() == s) { return i; } }
        return -1;
    }; $D.getMonthNumberFromName = function (name) {
        var n = $C.monthNames, m = $C.abbreviatedMonthNames, s = name.toLowerCase(); for (var i = 0; i < n.length; i++) { if (n[i].toLowerCase() == s || m[i].toLowerCase() == s) { return i; } }
        return -1;
    }; $D.isLeapYear = function (year) { return ((year % 4 === 0 && year % 100 !== 0) || year % 400 === 0); }; $D.getDaysInMonth = function (year, month) { return [31, ($D.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month]; }; $D.getTimezoneAbbreviation = function (offset) {
        var z = $C.timezones, p; for (var i = 0; i < z.length; i++) { if (z[i].offset === offset) { return z[i].name; } }
        return null;
    }; $D.getTimezoneOffset = function (name) {
        var z = $C.timezones, p; for (var i = 0; i < z.length; i++) { if (z[i].name === name.toUpperCase()) { return z[i].offset; } }
        return null;
    }; $P.clone = function () { return new Date(this.getTime()); }; $P.compareTo = function (date) { return Date.compare(this, date); }; $P.equals = function (date) { return Date.equals(this, date || new Date()); }; $P.between = function (start, end) { return this.getTime() >= start.getTime() && this.getTime() <= end.getTime(); }; $P.isAfter = function (date) { return this.compareTo(date || new Date()) === 1; }; $P.isBefore = function (date) { return (this.compareTo(date || new Date()) === -1); }; $P.isToday = function () { return this.isSameDay(new Date()); }; $P.isSameDay = function (date) { return this.clone().clearTime().equals(date.clone().clearTime()); }; $P.addMilliseconds = function (value) { this.setMilliseconds(this.getMilliseconds() + value); return this; }; $P.addSeconds = function (value) { return this.addMilliseconds(value * 1000); }; $P.addMinutes = function (value) { return this.addMilliseconds(value * 60000); }; $P.addHours = function (value) { return this.addMilliseconds(value * 3600000); }; $P.addDays = function (value) { this.setDate(this.getDate() + value); return this; }; $P.addWeeks = function (value) { return this.addDays(value * 7); }; $P.addMonths = function (value) { var n = this.getDate(); this.setDate(1); this.setMonth(this.getMonth() + value); this.setDate(Math.min(n, $D.getDaysInMonth(this.getFullYear(), this.getMonth()))); return this; }; $P.addYears = function (value) { return this.addMonths(value * 12); }; $P.add = function (config) {
        if (typeof config == "number") { this._orient = config; return this; }
        var x = config; if (x.milliseconds) { this.addMilliseconds(x.milliseconds); }
        if (x.seconds) { this.addSeconds(x.seconds); }
        if (x.minutes) { this.addMinutes(x.minutes); }
        if (x.hours) { this.addHours(x.hours); }
        if (x.weeks) { this.addWeeks(x.weeks); }
        if (x.months) { this.addMonths(x.months); }
        if (x.years) { this.addYears(x.years); }
        if (x.days) { this.addDays(x.days); }
        return this;
    }; var $y, $m, $d; $P.getWeek = function () {
        var a, b, c, d, e, f, g, n, s, w; $y = (!$y) ? this.getFullYear() : $y; $m = (!$m) ? this.getMonth() + 1 : $m; $d = (!$d) ? this.getDate() : $d; if ($m <= 2) { a = $y - 1; b = (a / 4 | 0) - (a / 100 | 0) + (a / 400 | 0); c = ((a - 1) / 4 | 0) - ((a - 1) / 100 | 0) + ((a - 1) / 400 | 0); s = b - c; e = 0; f = $d - 1 + (31 * ($m - 1)); } else { a = $y; b = (a / 4 | 0) - (a / 100 | 0) + (a / 400 | 0); c = ((a - 1) / 4 | 0) - ((a - 1) / 100 | 0) + ((a - 1) / 400 | 0); s = b - c; e = s + 1; f = $d + ((153 * ($m - 3) + 2) / 5) + 58 + s; }
        g = (a + b) % 7; d = (f + g - e) % 7; n = (f + 3 - d) | 0; if (n < 0) { w = 53 - ((g - s) / 5 | 0); } else if (n > 364 + s) { w = 1; } else { w = (n / 7 | 0) + 1; }
        $y = $m = $d = null; return w;
    }; $P.getISOWeek = function () { $y = this.getUTCFullYear(); $m = this.getUTCMonth() + 1; $d = this.getUTCDate(); return p(this.getWeek()); }; $P.setWeek = function (n) { return this.moveToDayOfWeek(1).addWeeks(n - this.getWeek()); }; $D._validate = function (n, min, max, name) {
        if (typeof n == "undefined") { return false; } else if (typeof n != "number") { throw new TypeError(n + " is not a Number."); } else if (n < min || n > max) { throw new RangeError(n + " is not a valid value for " + name + "."); }
        return true;
    }; $D.validateMillisecond = function (value) { return $D._validate(value, 0, 999, "millisecond"); }; $D.validateSecond = function (value) { return $D._validate(value, 0, 59, "second"); }; $D.validateMinute = function (value) { return $D._validate(value, 0, 59, "minute"); }; $D.validateHour = function (value) { return $D._validate(value, 0, 23, "hour"); }; $D.validateDay = function (value, year, month) { return $D._validate(value, 1, $D.getDaysInMonth(year, month), "day"); }; $D.validateMonth = function (value) { return $D._validate(value, 0, 11, "month"); }; $D.validateYear = function (value) { return $D._validate(value, 0, 9999, "year"); }; $P.set = function (config) {
        if ($D.validateMillisecond(config.millisecond)) { this.addMilliseconds(config.millisecond - this.getMilliseconds()); }
        if ($D.validateSecond(config.second)) { this.addSeconds(config.second - this.getSeconds()); }
        if ($D.validateMinute(config.minute)) { this.addMinutes(config.minute - this.getMinutes()); }
        if ($D.validateHour(config.hour)) { this.addHours(config.hour - this.getHours()); }
        if ($D.validateMonth(config.month)) { this.addMonths(config.month - this.getMonth()); }
        if ($D.validateYear(config.year)) { this.addYears(config.year - this.getFullYear()); }
        if ($D.validateDay(config.day, this.getFullYear(), this.getMonth())) { this.addDays(config.day - this.getDate()); }
        if (config.timezone) { this.setTimezone(config.timezone); }
        if (config.timezoneOffset) { this.setTimezoneOffset(config.timezoneOffset); }
        if (config.week && $D._validate(config.week, 0, 53, "week")) { this.setWeek(config.week); }
        return this;
    }; $P.moveToFirstDayOfMonth = function () { return this.set({ day: 1 }); }; $P.moveToLastDayOfMonth = function () { return this.set({ day: $D.getDaysInMonth(this.getFullYear(), this.getMonth()) }); }; $P.moveToNthOccurrence = function (dayOfWeek, occurrence) {
        var shift = 0; if (occurrence > 0) { shift = occurrence - 1; }
        else if (occurrence === -1) {
            this.moveToLastDayOfMonth(); if (this.getDay() !== dayOfWeek) { this.moveToDayOfWeek(dayOfWeek, -1); }
            return this;
        }
        return this.moveToFirstDayOfMonth().addDays(-1).moveToDayOfWeek(dayOfWeek, +1).addWeeks(shift);
    }; $P.moveToDayOfWeek = function (dayOfWeek, orient) { var diff = (dayOfWeek - this.getDay() + 7 * (orient || +1)) % 7; return this.addDays((diff === 0) ? diff += 7 * (orient || +1) : diff); }; $P.moveToMonth = function (month, orient) { var diff = (month - this.getMonth() + 12 * (orient || +1)) % 12; return this.addMonths((diff === 0) ? diff += 12 * (orient || +1) : diff); }; $P.getOrdinalNumber = function () { return Math.ceil((this.clone().clearTime() - new Date(this.getFullYear(), 0, 1)) / 86400000) + 1; }; $P.getTimezone = function () { return $D.getTimezoneAbbreviation(this.getUTCOffset()); }; $P.setTimezoneOffset = function (offset) { var here = this.getTimezoneOffset(), there = Number(offset) * -6 / 10; return this.addMinutes(there - here); }; $P.setTimezone = function (offset) { return this.setTimezoneOffset($D.getTimezoneOffset(offset)); }; $P.hasDaylightSavingTime = function () { return (Date.today().set({ month: 0, day: 1 }).getTimezoneOffset() !== Date.today().set({ month: 6, day: 1 }).getTimezoneOffset()); }; $P.isDaylightSavingTime = function () { return (this.hasDaylightSavingTime() && new Date().getTimezoneOffset() === Date.today().set({ month: 6, day: 1 }).getTimezoneOffset()); }; $P.getUTCOffset = function () { var n = this.getTimezoneOffset() * -10 / 6, r; if (n < 0) { r = (n - 10000).toString(); return r.charAt(0) + r.substr(2); } else { r = (n + 10000).toString(); return "+" + r.substr(1); } }; $P.getElapsed = function (date) { return (date || new Date()) - this; }; if (!$P.toISOString) {
        $P.toISOString = function () {
            function f(n) { return n < 10 ? '0' + n : n; }
            return '"' + this.getUTCFullYear() + '-' +
            f(this.getUTCMonth() + 1) + '-' +
            f(this.getUTCDate()) + 'T' +
            f(this.getUTCHours()) + ':' +
            f(this.getUTCMinutes()) + ':' +
            f(this.getUTCSeconds()) + 'Z"';
        };
    }
    $P._toString = $P.toString; $P.toString = function (format) {
        var x = this; if (format && format.length == 1) { var c = $C.formatPatterns; x.t = x.toString; switch (format) { case "d": return x.t(c.shortDate); case "D": return x.t(c.longDate); case "F": return x.t(c.fullDateTime); case "m": return x.t(c.monthDay); case "r": return x.t(c.rfc1123); case "s": return x.t(c.sortableDateTime); case "t": return x.t(c.shortTime); case "T": return x.t(c.longTime); case "u": return x.t(c.universalSortableDateTime); case "y": return x.t(c.yearMonth); } }
        var ord = function (n) { switch (n * 1) { case 1: case 21: case 31: return "st"; case 2: case 22: return "nd"; case 3: case 23: return "rd"; default: return "th"; } }; return format ? format.replace(/(\\)?(dd?d?d?|MM?M?M?|yy?y?y?|hh?|HH?|mm?|ss?|tt?|S)/g, function (m) {
            if (m.charAt(0) === "\\") { return m.replace("\\", ""); }
            x.h = x.getHours; switch (m) { case "hh": return p(x.h() < 13 ? (x.h() === 0 ? 12 : x.h()) : (x.h() - 12)); case "h": return x.h() < 13 ? (x.h() === 0 ? 12 : x.h()) : (x.h() - 12); case "HH": return p(x.h()); case "H": return x.h(); case "mm": return p(x.getMinutes()); case "m": return x.getMinutes(); case "ss": return p(x.getSeconds()); case "s": return x.getSeconds(); case "yyyy": return p(x.getFullYear(), 4); case "yy": return p(x.getFullYear()); case "dddd": return $C.dayNames[x.getDay()]; case "ddd": return $C.abbreviatedDayNames[x.getDay()]; case "dd": return p(x.getDate()); case "d": return x.getDate(); case "MMMM": return $C.monthNames[x.getMonth()]; case "MMM": return $C.abbreviatedMonthNames[x.getMonth()]; case "MM": return p((x.getMonth() + 1)); case "M": return x.getMonth() + 1; case "t": return x.h() < 12 ? $C.amDesignator.substring(0, 1) : $C.pmDesignator.substring(0, 1); case "tt": return x.h() < 12 ? $C.amDesignator : $C.pmDesignator; case "S": return ord(x.getDate()); default: return m; }
        }) : this._toString();
    };
}());
(function () {
    var $D = Date, $P = $D.prototype, $C = $D.CultureInfo, $N = Number.prototype; $P._orient = +1; $P._nth = null; $P._is = false; $P._same = false; $P._isSecond = false; $N._dateElement = "day"; $P.next = function () { this._orient = +1; return this; }; $D.next = function () { return $D.today().next(); }; $P.last = $P.prev = $P.previous = function () { this._orient = -1; return this; }; $D.last = $D.prev = $D.previous = function () { return $D.today().last(); }; $P.is = function () { this._is = true; return this; }; $P.same = function () { this._same = true; this._isSecond = false; return this; }; $P.today = function () { return this.same().day(); }; $P.weekday = function () {
        if (this._is) { this._is = false; return (!this.is().sat() && !this.is().sun()); }
        return false;
    }; $P.at = function (time) { return (typeof time === "string") ? $D.parse(this.toString("d") + " " + time) : this.set(time); }; $N.fromNow = $N.after = function (date) { var c = {}; c[this._dateElement] = this; return ((!date) ? new Date() : date.clone()).add(c); }; $N.ago = $N.before = function (date) { var c = {}; c[this._dateElement] = this * -1; return ((!date) ? new Date() : date.clone()).add(c); }; var dx = ("sunday monday tuesday wednesday thursday friday saturday").split(/\s/), mx = ("january february march april may june july august september october november december").split(/\s/), px = ("Millisecond Second Minute Hour Day Week Month Year").split(/\s/), pxf = ("Milliseconds Seconds Minutes Hours Date Week Month FullYear").split(/\s/), nth = ("final first second third fourth fifth").split(/\s/), de; $P.toObject = function () {
        var o = {}; for (var i = 0; i < px.length; i++) { o[px[i].toLowerCase()] = this["get" + pxf[i]](); }
        return o;
    }; $D.fromObject = function (config) { config.week = null; return Date.today().set(config); }; var df = function (n) {
        return function () {
            if (this._is) { this._is = false; return this.getDay() == n; }
            if (this._nth !== null) {
                if (this._isSecond) { this.addSeconds(this._orient * -1); }
                this._isSecond = false; var ntemp = this._nth; this._nth = null; var temp = this.clone().moveToLastDayOfMonth(); this.moveToNthOccurrence(n, ntemp); if (this > temp) { throw new RangeError($D.getDayName(n) + " does not occur " + ntemp + " times in the month of " + $D.getMonthName(temp.getMonth()) + " " + temp.getFullYear() + "."); }
                return this;
            }
            return this.moveToDayOfWeek(n, this._orient);
        };
    }; var sdf = function (n) {
        return function () {
            var t = $D.today(), shift = n - t.getDay(); if (n === 0 && $C.firstDayOfWeek === 1 && t.getDay() !== 0) { shift = shift + 7; }
            return t.addDays(shift);
        };
    }; for (var i = 0; i < dx.length; i++) { $D[dx[i].toUpperCase()] = $D[dx[i].toUpperCase().substring(0, 3)] = i; $D[dx[i]] = $D[dx[i].substring(0, 3)] = sdf(i); $P[dx[i]] = $P[dx[i].substring(0, 3)] = df(i); }
    var mf = function (n) {
        return function () {
            if (this._is) { this._is = false; return this.getMonth() === n; }
            return this.moveToMonth(n, this._orient);
        };
    }; var smf = function (n) { return function () { return $D.today().set({ month: n, day: 1 }); }; }; for (var j = 0; j < mx.length; j++) { $D[mx[j].toUpperCase()] = $D[mx[j].toUpperCase().substring(0, 3)] = j; $D[mx[j]] = $D[mx[j].substring(0, 3)] = smf(j); $P[mx[j]] = $P[mx[j].substring(0, 3)] = mf(j); }
    var ef = function (j) {
        return function () {
            if (this._isSecond) { this._isSecond = false; return this; }
            if (this._same) {
                this._same = this._is = false; var o1 = this.toObject(), o2 = (arguments[0] || new Date()).toObject(), v = "", k = j.toLowerCase(); for (var m = (px.length - 1) ; m > -1; m--) {
                    v = px[m].toLowerCase(); if (o1[v] != o2[v]) { return false; }
                    if (k == v) { break; }
                }
                return true;
            }
            if (j.substring(j.length - 1) != "s") { j += "s"; }
            return this["add" + j](this._orient);
        };
    }; var nf = function (n) { return function () { this._dateElement = n; return this; }; }; for (var k = 0; k < px.length; k++) { de = px[k].toLowerCase(); $P[de] = $P[de + "s"] = ef(px[k]); $N[de] = $N[de + "s"] = nf(de); }
    $P._ss = ef("Second"); var nthfn = function (n) {
        return function (dayOfWeek) {
            if (this._same) { return this._ss(arguments[0]); }
            if (dayOfWeek || dayOfWeek === 0) { return this.moveToNthOccurrence(dayOfWeek, n); }
            this._nth = n; if (n === 2 && (dayOfWeek === undefined || dayOfWeek === null)) { this._isSecond = true; return this.addSeconds(this._orient); }
            return this;
        };
    }; for (var l = 0; l < nth.length; l++) { $P[nth[l]] = (l === 0) ? nthfn(-1) : nthfn(l); }
}());
(function () {
    Date.Parsing = { Exception: function (s) { this.message = "Parse error at '" + s.substring(0, 10) + " ...'"; } }; var $P = Date.Parsing; var _ = $P.Operators = {
        rtoken: function (r) { return function (s) { var mx = s.match(r); if (mx) { return ([mx[0], s.substring(mx[0].length)]); } else { throw new $P.Exception(s); } }; }, token: function (s) { return function (s) { return _.rtoken(new RegExp("^\s*" + s + "\s*"))(s); }; }, stoken: function (s) { return _.rtoken(new RegExp("^" + s)); }, until: function (p) {
            return function (s) {
                var qx = [], rx = null; while (s.length) {
                    try { rx = p.call(this, s); } catch (e) { qx.push(rx[0]); s = rx[1]; continue; }
                    break;
                }
                return [qx, s];
            };
        }, many: function (p) {
            return function (s) {
                var rx = [], r = null; while (s.length) {
                    try { r = p.call(this, s); } catch (e) { return [rx, s]; }
                    rx.push(r[0]); s = r[1];
                }
                return [rx, s];
            };
        }, optional: function (p) {
            return function (s) {
                var r = null; try { r = p.call(this, s); } catch (e) { return [null, s]; }
                return [r[0], r[1]];
            };
        }, not: function (p) {
            return function (s) {
                try { p.call(this, s); } catch (e) { return [null, s]; }
                throw new $P.Exception(s);
            };
        }, ignore: function (p) { return p ? function (s) { var r = null; r = p.call(this, s); return [null, r[1]]; } : null; }, product: function () {
            var px = arguments[0], qx = Array.prototype.slice.call(arguments, 1), rx = []; for (var i = 0; i < px.length; i++) { rx.push(_.each(px[i], qx)); }
            return rx;
        }, cache: function (rule) {
            var cache = {}, r = null; return function (s) {
                try { r = cache[s] = (cache[s] || rule.call(this, s)); } catch (e) { r = cache[s] = e; }
                if (r instanceof $P.Exception) { throw r; } else { return r; }
            };
        }, any: function () {
            var px = arguments; return function (s) {
                var r = null; for (var i = 0; i < px.length; i++) {
                    if (px[i] == null) { continue; }
                    try { r = (px[i].call(this, s)); } catch (e) { r = null; }
                    if (r) { return r; }
                }
                throw new $P.Exception(s);
            };
        }, each: function () {
            var px = arguments; return function (s) {
                var rx = [], r = null; for (var i = 0; i < px.length; i++) {
                    if (px[i] == null) { continue; }
                    try { r = (px[i].call(this, s)); } catch (e) { throw new $P.Exception(s); }
                    rx.push(r[0]); s = r[1];
                }
                return [rx, s];
            };
        }, all: function () { var px = arguments, _ = _; return _.each(_.optional(px)); }, sequence: function (px, d, c) {
            d = d || _.rtoken(/^\s*/); c = c || null; if (px.length == 1) { return px[0]; }
            return function (s) {
                var r = null, q = null; var rx = []; for (var i = 0; i < px.length; i++) {
                    try { r = px[i].call(this, s); } catch (e) { break; }
                    rx.push(r[0]); try { q = d.call(this, r[1]); } catch (ex) { q = null; break; }
                    s = q[1];
                }
                if (!r) { throw new $P.Exception(s); }
                if (q) { throw new $P.Exception(q[1]); }
                if (c) { try { r = c.call(this, r[1]); } catch (ey) { throw new $P.Exception(r[1]); } }
                return [rx, (r ? r[1] : s)];
            };
        }, between: function (d1, p, d2) { d2 = d2 || d1; var _fn = _.each(_.ignore(d1), p, _.ignore(d2)); return function (s) { var rx = _fn.call(this, s); return [[rx[0][0], r[0][2]], rx[1]]; }; }, list: function (p, d, c) { d = d || _.rtoken(/^\s*/); c = c || null; return (p instanceof Array ? _.each(_.product(p.slice(0, -1), _.ignore(d)), p.slice(-1), _.ignore(c)) : _.each(_.many(_.each(p, _.ignore(d))), px, _.ignore(c))); }, set: function (px, d, c) {
            d = d || _.rtoken(/^\s*/); c = c || null; return function (s) {
                var r = null, p = null, q = null, rx = null, best = [[], s], last = false; for (var i = 0; i < px.length; i++) {
                    q = null; p = null; r = null; last = (px.length == 1); try { r = px[i].call(this, s); } catch (e) { continue; }
                    rx = [[r[0]], r[1]]; if (r[1].length > 0 && !last) { try { q = d.call(this, r[1]); } catch (ex) { last = true; } } else { last = true; }
                    if (!last && q[1].length === 0) { last = true; }
                    if (!last) {
                        var qx = []; for (var j = 0; j < px.length; j++) { if (i != j) { qx.push(px[j]); } }
                        p = _.set(qx, d).call(this, q[1]); if (p[0].length > 0) { rx[0] = rx[0].concat(p[0]); rx[1] = p[1]; }
                    }
                    if (rx[1].length < best[1].length) { best = rx; }
                    if (best[1].length === 0) { break; }
                }
                if (best[0].length === 0) { return best; }
                if (c) {
                    try { q = c.call(this, best[1]); } catch (ey) { throw new $P.Exception(best[1]); }
                    best[1] = q[1];
                }
                return best;
            };
        }, forward: function (gr, fname) { return function (s) { return gr[fname].call(this, s); }; }, replace: function (rule, repl) { return function (s) { var r = rule.call(this, s); return [repl, r[1]]; }; }, process: function (rule, fn) { return function (s) { var r = rule.call(this, s); return [fn.call(this, r[0]), r[1]]; }; }, min: function (min, rule) {
            return function (s) {
                var rx = rule.call(this, s); if (rx[0].length < min) { throw new $P.Exception(s); }
                return rx;
            };
        }
    }; var _generator = function (op) {
        return function () {
            var args = null, rx = []; if (arguments.length > 1) { args = Array.prototype.slice.call(arguments); } else if (arguments[0] instanceof Array) { args = arguments[0]; }
            if (args) { for (var i = 0, px = args.shift() ; i < px.length; i++) { args.unshift(px[i]); rx.push(op.apply(null, args)); args.shift(); return rx; } } else { return op.apply(null, arguments); }
        };
    }; var gx = "optional not ignore cache".split(/\s/); for (var i = 0; i < gx.length; i++) { _[gx[i]] = _generator(_[gx[i]]); }
    var _vector = function (op) { return function () { if (arguments[0] instanceof Array) { return op.apply(null, arguments[0]); } else { return op.apply(null, arguments); } }; }; var vx = "each any all".split(/\s/); for (var j = 0; j < vx.length; j++) { _[vx[j]] = _vector(_[vx[j]]); }
}()); (function () {
    var $D = Date, $P = $D.prototype, $C = $D.CultureInfo; var flattenAndCompact = function (ax) {
        var rx = []; for (var i = 0; i < ax.length; i++) { if (ax[i] instanceof Array) { rx = rx.concat(flattenAndCompact(ax[i])); } else { if (ax[i]) { rx.push(ax[i]); } } }
        return rx;
    }; $D.Grammar = {}; $D.Translator = {
        hour: function (s) { return function () { this.hour = Number(s); }; }, minute: function (s) { return function () { this.minute = Number(s); }; }, second: function (s) { return function () { this.second = Number(s); }; }, meridian: function (s) { return function () { this.meridian = s.slice(0, 1).toLowerCase(); }; }, timezone: function (s) { return function () { var n = s.replace(/[^\d\+\-]/g, ""); if (n.length) { this.timezoneOffset = Number(n); } else { this.timezone = s.toLowerCase(); } }; }, day: function (x) { var s = x[0]; return function () { this.day = Number(s.match(/\d+/)[0]); }; }, month: function (s) { return function () { this.month = (s.length == 3) ? "jan feb mar apr may jun jul aug sep oct nov dec".indexOf(s) / 4 : Number(s) - 1; }; }, year: function (s) { return function () { var n = Number(s); this.year = ((s.length > 2) ? n : (n + (((n + 2000) < $C.twoDigitYearMax) ? 2000 : 1900))); }; }, rday: function (s) { return function () { switch (s) { case "yesterday": this.days = -1; break; case "tomorrow": this.days = 1; break; case "today": this.days = 0; break; case "now": this.days = 0; this.now = true; break; } }; }, finishExact: function (x) {
            x = (x instanceof Array) ? x : [x]; for (var i = 0; i < x.length; i++) { if (x[i]) { x[i].call(this); } }
            var now = new Date(); if ((this.hour || this.minute) && (!this.month && !this.year && !this.day)) { this.day = now.getDate(); }
            if (!this.year) { this.year = now.getFullYear(); }
            if (!this.month && this.month !== 0) { this.month = now.getMonth(); }
            if (!this.day) { this.day = 1; }
            if (!this.hour) { this.hour = 0; }
            if (!this.minute) { this.minute = 0; }
            if (!this.second) { this.second = 0; }
            if (this.meridian && this.hour) { if (this.meridian == "p" && this.hour < 12) { this.hour = this.hour + 12; } else if (this.meridian == "a" && this.hour == 12) { this.hour = 0; } }
            if (this.day > $D.getDaysInMonth(this.year, this.month)) { throw new RangeError(this.day + " is not a valid value for days."); }
            var r = new Date(this.year, this.month, this.day, this.hour, this.minute, this.second); if (this.timezone) { r.set({ timezone: this.timezone }); } else if (this.timezoneOffset) { r.set({ timezoneOffset: this.timezoneOffset }); }
            return r;
        }, finish: function (x) {
            x = (x instanceof Array) ? flattenAndCompact(x) : [x]; if (x.length === 0) { return null; }
            for (var i = 0; i < x.length; i++) { if (typeof x[i] == "function") { x[i].call(this); } }
            var today = $D.today(); if (this.now && !this.unit && !this.operator) { return new Date(); } else if (this.now) { today = new Date(); }
            var expression = !!(this.days && this.days !== null || this.orient || this.operator); var gap, mod, orient; orient = ((this.orient == "past" || this.operator == "subtract") ? -1 : 1); if (!this.now && "hour minute second".indexOf(this.unit) != -1) { today.setTimeToNow(); }
            if (this.month || this.month === 0) { if ("year day hour minute second".indexOf(this.unit) != -1) { this.value = this.month + 1; this.month = null; expression = true; } }
            if (!expression && this.weekday && !this.day && !this.days) {
                var temp = Date[this.weekday](); this.day = temp.getDate(); if (!this.month) { this.month = temp.getMonth(); }
                this.year = temp.getFullYear();
            }
            if (expression && this.weekday && this.unit != "month") { this.unit = "day"; gap = ($D.getDayNumberFromName(this.weekday) - today.getDay()); mod = 7; this.days = gap ? ((gap + (orient * mod)) % mod) : (orient * mod); }
            if (this.month && this.unit == "day" && this.operator) { this.value = (this.month + 1); this.month = null; }
            if (this.value != null && this.month != null && this.year != null) { this.day = this.value * 1; }
            if (this.month && !this.day && this.value) { today.set({ day: this.value * 1 }); if (!expression) { this.day = this.value * 1; } }
            if (!this.month && this.value && this.unit == "month" && !this.now) { this.month = this.value; expression = true; }
            if (expression && (this.month || this.month === 0) && this.unit != "year") { this.unit = "month"; gap = (this.month - today.getMonth()); mod = 12; this.months = gap ? ((gap + (orient * mod)) % mod) : (orient * mod); this.month = null; }
            if (!this.unit) { this.unit = "day"; }
            if (!this.value && this.operator && this.operator !== null && this[this.unit + "s"] && this[this.unit + "s"] !== null) { this[this.unit + "s"] = this[this.unit + "s"] + ((this.operator == "add") ? 1 : -1) + (this.value || 0) * orient; } else if (this[this.unit + "s"] == null || this.operator != null) {
                if (!this.value) { this.value = 1; }
                this[this.unit + "s"] = this.value * orient;
            }
            if (this.meridian && this.hour) { if (this.meridian == "p" && this.hour < 12) { this.hour = this.hour + 12; } else if (this.meridian == "a" && this.hour == 12) { this.hour = 0; } }
            if (this.weekday && !this.day && !this.days) { var temp = Date[this.weekday](); this.day = temp.getDate(); if (temp.getMonth() !== today.getMonth()) { this.month = temp.getMonth(); } }
            if ((this.month || this.month === 0) && !this.day) { this.day = 1; }
            if (!this.orient && !this.operator && this.unit == "week" && this.value && !this.day && !this.month) { return Date.today().setWeek(this.value); }
            if (expression && this.timezone && this.day && this.days) { this.day = this.days; }
            return (expression) ? today.add(this) : today.set(this);
        }
    }; var _ = $D.Parsing.Operators, g = $D.Grammar, t = $D.Translator, _fn; g.datePartDelimiter = _.rtoken(/^([\s\-\.\,\/\x27]+)/); g.timePartDelimiter = _.stoken(":"); g.whiteSpace = _.rtoken(/^\s*/); g.generalDelimiter = _.rtoken(/^(([\s\,]|at|@|on)+)/); var _C = {}; g.ctoken = function (keys) {
        var fn = _C[keys]; if (!fn) {
            var c = $C.regexPatterns; var kx = keys.split(/\s+/), px = []; for (var i = 0; i < kx.length; i++) { px.push(_.replace(_.rtoken(c[kx[i]]), kx[i])); }
            fn = _C[keys] = _.any.apply(null, px);
        }
        return fn;
    }; g.ctoken2 = function (key) { return _.rtoken($C.regexPatterns[key]); }; g.h = _.cache(_.process(_.rtoken(/^(0[0-9]|1[0-2]|[1-9])/), t.hour)); g.hh = _.cache(_.process(_.rtoken(/^(0[0-9]|1[0-2])/), t.hour)); g.H = _.cache(_.process(_.rtoken(/^([0-1][0-9]|2[0-3]|[0-9])/), t.hour)); g.HH = _.cache(_.process(_.rtoken(/^([0-1][0-9]|2[0-3])/), t.hour)); g.m = _.cache(_.process(_.rtoken(/^([0-5][0-9]|[0-9])/), t.minute)); g.mm = _.cache(_.process(_.rtoken(/^[0-5][0-9]/), t.minute)); g.s = _.cache(_.process(_.rtoken(/^([0-5][0-9]|[0-9])/), t.second)); g.ss = _.cache(_.process(_.rtoken(/^[0-5][0-9]/), t.second)); g.hms = _.cache(_.sequence([g.H, g.m, g.s], g.timePartDelimiter)); g.t = _.cache(_.process(g.ctoken2("shortMeridian"), t.meridian)); g.tt = _.cache(_.process(g.ctoken2("longMeridian"), t.meridian)); g.z = _.cache(_.process(_.rtoken(/^((\+|\-)\s*\d\d\d\d)|((\+|\-)\d\d\:?\d\d)/), t.timezone)); g.zz = _.cache(_.process(_.rtoken(/^((\+|\-)\s*\d\d\d\d)|((\+|\-)\d\d\:?\d\d)/), t.timezone)); g.zzz = _.cache(_.process(g.ctoken2("timezone"), t.timezone)); g.timeSuffix = _.each(_.ignore(g.whiteSpace), _.set([g.tt, g.zzz])); g.time = _.each(_.optional(_.ignore(_.stoken("T"))), g.hms, g.timeSuffix); g.d = _.cache(_.process(_.each(_.rtoken(/^([0-2]\d|3[0-1]|\d)/), _.optional(g.ctoken2("ordinalSuffix"))), t.day)); g.dd = _.cache(_.process(_.each(_.rtoken(/^([0-2]\d|3[0-1])/), _.optional(g.ctoken2("ordinalSuffix"))), t.day)); g.ddd = g.dddd = _.cache(_.process(g.ctoken("sun mon tue wed thu fri sat"), function (s) { return function () { this.weekday = s; }; })); g.M = _.cache(_.process(_.rtoken(/^(1[0-2]|0\d|\d)/), t.month)); g.MM = _.cache(_.process(_.rtoken(/^(1[0-2]|0\d)/), t.month)); g.MMM = g.MMMM = _.cache(_.process(g.ctoken("jan feb mar apr may jun jul aug sep oct nov dec"), t.month)); g.y = _.cache(_.process(_.rtoken(/^(\d\d?)/), t.year)); g.yy = _.cache(_.process(_.rtoken(/^(\d\d)/), t.year)); g.yyy = _.cache(_.process(_.rtoken(/^(\d\d?\d?\d?)/), t.year)); g.yyyy = _.cache(_.process(_.rtoken(/^(\d\d\d\d)/), t.year)); _fn = function () { return _.each(_.any.apply(null, arguments), _.not(g.ctoken2("timeContext"))); }; g.day = _fn(g.d, g.dd); g.month = _fn(g.M, g.MMM); g.year = _fn(g.yyyy, g.yy); g.orientation = _.process(g.ctoken("past future"), function (s) { return function () { this.orient = s; }; }); g.operator = _.process(g.ctoken("add subtract"), function (s) { return function () { this.operator = s; }; }); g.rday = _.process(g.ctoken("yesterday tomorrow today now"), t.rday); g.unit = _.process(g.ctoken("second minute hour day week month year"), function (s) { return function () { this.unit = s; }; }); g.value = _.process(_.rtoken(/^\d\d?(st|nd|rd|th)?/), function (s) { return function () { this.value = s.replace(/\D/g, ""); }; }); g.expression = _.set([g.rday, g.operator, g.value, g.unit, g.orientation, g.ddd, g.MMM]); _fn = function () { return _.set(arguments, g.datePartDelimiter); }; g.mdy = _fn(g.ddd, g.month, g.day, g.year); g.ymd = _fn(g.ddd, g.year, g.month, g.day); g.dmy = _fn(g.ddd, g.day, g.month, g.year); g.date = function (s) { return ((g[$C.dateElementOrder] || g.mdy).call(this, s)); }; g.format = _.process(_.many(_.any(_.process(_.rtoken(/^(dd?d?d?|MM?M?M?|yy?y?y?|hh?|HH?|mm?|ss?|tt?|zz?z?)/), function (fmt) { if (g[fmt]) { return g[fmt]; } else { throw $D.Parsing.Exception(fmt); } }), _.process(_.rtoken(/^[^dMyhHmstz]+/), function (s) { return _.ignore(_.stoken(s)); }))), function (rules) { return _.process(_.each.apply(null, rules), t.finishExact); }); var _F = {}; var _get = function (f) { return _F[f] = (_F[f] || g.format(f)[0]); }; g.formats = function (fx) {
        if (fx instanceof Array) {
            var rx = []; for (var i = 0; i < fx.length; i++) { rx.push(_get(fx[i])); }
            return _.any.apply(null, rx);
        } else { return _get(fx); }
    }; g._formats = g.formats(["\"yyyy-MM-ddTHH:mm:ssZ\"", "yyyy-MM-ddTHH:mm:ssZ", "yyyy-MM-ddTHH:mm:ssz", "yyyy-MM-ddTHH:mm:ss", "yyyy-MM-ddTHH:mmZ", "yyyy-MM-ddTHH:mmz", "yyyy-MM-ddTHH:mm", "ddd, MMM dd, yyyy H:mm:ss tt", "ddd MMM d yyyy HH:mm:ss zzz", "MMddyyyy", "ddMMyyyy", "Mddyyyy", "ddMyyyy", "Mdyyyy", "dMyyyy", "yyyy", "Mdyy", "dMyy", "d"]); g._start = _.process(_.set([g.date, g.time, g.expression], g.generalDelimiter, g.whiteSpace), t.finish); g.start = function (s) {
        try { var r = g._formats.call({}, s); if (r[1].length === 0) { return r; } } catch (e) { }
        return g._start.call({}, s);
    }; $D._parse = $D.parse; $D.parse = function (s) {
        var r = null; if (!s) { return null; }
        if (s instanceof Date) { return s; }
        try { r = $D.Grammar.start.call({}, s.replace(/^\s*(\S*(\s+\S+)*)\s*$/, "$1")); } catch (e) { return null; }
        return ((r[1].length === 0) ? r[0] : null);
    }; $D.getParseFunction = function (fx) {
        var fn = $D.Grammar.formats(fx); return function (s) {
            var r = null; try { r = fn.call({}, s); } catch (e) { return null; }
            return ((r[1].length === 0) ? r[0] : null);
        };
    }; $D.parseExact = function (s, fx) { return $D.getParseFunction(fx)(s); };
}());
(function($){$.fn.html5Uploader=function(options){var crlf='\r\n';var boundary="iloveigloo";var dashes="--";var settings={"name":"uploadedFile","postUrl":"Upload.aspx","onClientAbort":null,"onClientError":null,"onClientLoad":null,"onClientLoadEnd":null,"onClientLoadStart":null,"onClientProgress":null,"onServerAbort":null,"onServerError":null,"onServerLoad":null,"onServerLoadStart":null,"onServerProgress":null,"onServerReadyStateChange":null,"onSuccess":null};if(options){$.extend(settings,options);}
return this.each(function(options){var $this=$(this);if($this.is("[type=\"file\"]")){$this.bind("change",function(){var files=this.files;for(var i=0;i<files.length;i++){fileHandler(files[i]);}});}else{$this.bind("dragenter dragover",function(){return false;}).bind("drop",function(e){var files=e.originalEvent.dataTransfer.files;for(var i=0;i<files.length;i++){fileHandler(files[i]);}
return false;});}});function fileHandler(file){var fileReader=new FileReader();fileReader.onabort=function(e){if(settings.onClientAbort){settings.onClientAbort(e,file);}};fileReader.onerror=function(e){if(settings.onClientError){settings.onClientError(e,file);}};fileReader.onload=function(e){if(settings.onClientLoad){settings.onClientLoad(e,file);}};fileReader.onloadend=function(e){if(settings.onClientLoadEnd){settings.onClientLoadEnd(e,file);}};fileReader.onloadstart=function(e){if(settings.onClientLoadStart){settings.onClientLoadStart(e,file);}};fileReader.onprogress=function(e){if(settings.onClientProgress){settings.onClientProgress(e,file);}};fileReader.readAsDataURL(file);var xmlHttpRequest=new XMLHttpRequest();xmlHttpRequest.upload.onabort=function(e){if(settings.onServerAbort){settings.onServerAbort(e,file);}};xmlHttpRequest.upload.onerror=function(e){if(settings.onServerError){settings.onServerError(e,file);}};xmlHttpRequest.upload.onload=function(e){if(settings.onServerLoad){settings.onServerLoad(e,file);}};xmlHttpRequest.upload.onloadstart=function(e){if(settings.onServerLoadStart){settings.onServerLoadStart(e,file);}};xmlHttpRequest.upload.onprogress=function(e){if(settings.onServerProgress){settings.onServerProgress(e,file);}};xmlHttpRequest.onreadystatechange=function(e){if(settings.onServerReadyStateChange){settings.onServerReadyStateChange(e,file,xmlHttpRequest.readyState);}
if(settings.onSuccess&&xmlHttpRequest.readyState==4&&xmlHttpRequest.status==200){settings.onSuccess(e,file,xmlHttpRequest.responseText);}};xmlHttpRequest.open("https://cdn4.tgdd.vn/v2015/Scripts/desktop/V5/POST",settings.postUrl,true);if(file.getAsBinary){var data=dashes+boundary+crlf+"Content-Disposition: form-data;"+"name=\""+settings.name+"\";"+"filename=\""+unescape(encodeURIComponent(file.name))+"\""+crlf+"Content-Type: application/octet-stream"+crlf+crlf+file.getAsBinary()+crlf+dashes+boundary+dashes;xmlHttpRequest.setRequestHeader("Content-Type","multipart/form-data;boundary="+boundary);xmlHttpRequest.sendAsBinary(data);}else if(window.FormData){var formData=new FormData();formData.append(settings.name,file);xmlHttpRequest.send(formData);}}};})(jQuery);
/* ===========================================================
* bootstrap-tooltip.js v2.2.2
* http://twitter.github.com/bootstrap/javascript.html#tooltips
* Inspired by the original jQuery.tipsy by Jason Frame
* ===========================================================
* Copyright 2012 Twitter, Inc.
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
* ========================================================== */


!function ($) {

    "use strict"; // jshint ;_;


    /* TOOLTIP PUBLIC CLASS DEFINITION
     * =============================== */

    var Tooltip = function (element, options) {
        this.init('tooltip', element, options)
    }

    Tooltip.prototype = {

        constructor: Tooltip

    , init: function (type, element, options) {
        var eventIn
          , eventOut

        this.type = type
        this.$element = $(element)
        this.options = this.getOptions(options)
        this.enabled = true

        if (this.options.trigger == 'click') {
            this.$element.on('click.' + this.type, this.options.selector, $.proxy(this.toggle, this))
        } else if (this.options.trigger != 'manual') {
            eventIn = this.options.trigger == 'hover' ? 'mouseenter' : 'focus'
            eventOut = this.options.trigger == 'hover' ? 'mouseleave' : 'blur'
            this.$element.on(eventIn + '.' + this.type, this.options.selector, $.proxy(this.enter, this))
            this.$element.on(eventOut + '.' + this.type, this.options.selector, $.proxy(this.leave, this))
        }

        this.options.selector ?
          (this._options = $.extend({}, this.options, { trigger: 'manual', selector: '' })) :
          this.fixTitle()
    }

    , getOptions: function (options) {
        options = $.extend({}, $.fn[this.type].defaults, options, this.$element.data())

        if (options.delay && typeof options.delay == 'number') {
            options.delay = {
                show: options.delay
            , hide: options.delay
            }
        }

        return options
    }

    , enter: function (e) {
        var self = $(e.currentTarget)[this.type](this._options).data(this.type)

        if (!self.options.delay || !self.options.delay.show) return self.show()

        clearTimeout(this.timeout)
        self.hoverState = 'in'
        this.timeout = setTimeout(function () {
            if (self.hoverState == 'in') self.show()
        }, self.options.delay.show)
    }

    , leave: function (e) {
        var self = $(e.currentTarget)[this.type](this._options).data(this.type)

        if (this.timeout) clearTimeout(this.timeout)
        if (!self.options.delay || !self.options.delay.hide) return self.hide()

        self.hoverState = 'out'
        this.timeout = setTimeout(function () {
            if (self.hoverState == 'out') self.hide()
        }, self.options.delay.hide)
    }

    , show: function () {
        var $tip
          , inside
          , pos
          , actualWidth
          , actualHeight
          , placement
          , tp

        if (this.hasContent() && this.enabled) {
            $tip = this.tip()
            this.setContent()

            if (this.options.animation) {
                $tip.addClass('fade')
            }

            placement = typeof this.options.placement == 'function' ?
              this.options.placement.call(this, $tip[0], this.$element[0]) :
              this.options.placement

            inside = /in/.test(placement)

            $tip
              .detach()
              .css({ top: 0, left: 0, display: 'block' })
              .insertAfter(this.$element)

            pos = this.getPosition(inside)

            actualWidth = $tip[0].offsetWidth
            actualHeight = $tip[0].offsetHeight

            switch (inside ? placement.split(' ')[1] : placement) {
                case 'bottom':
                    tp = { top: pos.top + pos.height, left: pos.left + pos.width / 2 - actualWidth / 2 }
                    break
                case 'top':
                    tp = { top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2 }
                    break
                case 'left':
                    tp = { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth }
                    break
                case 'right':
                    tp = { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width }
                    break
            }

            $tip
              .offset(tp)
              .addClass(placement)
              .addClass('in')
        }
    }

    , setContent: function () {
        var $tip = this.tip()
          , title = this.getTitle()

        $tip.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](title)
        $tip.removeClass('fade in top bottom left right')
    }

    , hide: function () {
        var that = this
          , $tip = this.tip()

        $tip.removeClass('in')

        function removeWithAnimation() {
            var timeout = setTimeout(function () {
                $tip.off($.support.transition.end).detach()
            }, 500)

            $tip.one($.support.transition.end, function () {
                clearTimeout(timeout)
                $tip.detach()
            })
        }

        $.support.transition && this.$tip.hasClass('fade') ?
          removeWithAnimation() :
          $tip.detach()

        return this
    }

    , fixTitle: function () {
        var $e = this.$element
        if ($e.attr('title') || typeof ($e.attr('data-original-title')) != 'string') {
            $e.attr('data-original-title', $e.attr('title') || '').removeAttr('title')
        }
    }

    , hasContent: function () {
        return this.getTitle()
    }

    , getPosition: function (inside) {
        return $.extend({}, (inside ? { top: 0, left: 0 } : this.$element.offset()), {
            width: this.$element[0].offsetWidth
        , height: this.$element[0].offsetHeight
        })
    }

    , getTitle: function () {
        var title
          , $e = this.$element
          , o = this.options

        title = $e.attr('data-original-title')
          || (typeof o.title == 'function' ? o.title.call($e[0]) : o.title)

        return title
    }

    , tip: function () {
        return this.$tip = this.$tip || $(this.options.template)
    }

    , validate: function () {
        if (!this.$element[0].parentNode) {
            this.hide()
            this.$element = null
            this.options = null
        }
    }

    , enable: function () {
        this.enabled = true
    }

    , disable: function () {
        this.enabled = false
    }

    , toggleEnabled: function () {
        this.enabled = !this.enabled
    }

    , toggle: function (e) {
        var self = $(e.currentTarget)[this.type](this._options).data(this.type)
        self[self.tip().hasClass('in') ? 'hide' : 'show']()
    }

    , destroy: function () {
        this.hide().$element.off('.' + this.type).removeData(this.type)
    }

    }


    /* TOOLTIP PLUGIN DEFINITION
     * ========================= */

    var old = $.fn.tooltip

    $.fn.tooltip = function (option) {
        return this.each(function () {
            var $this = $(this)
              , data = $this.data('tooltip')
              , options = typeof option == 'object' && option
            if (!data) $this.data('tooltip', (data = new Tooltip(this, options)))
            if (typeof option == 'string') data[option]()
        })
    }

    $.fn.tooltip.Constructor = Tooltip

    $.fn.tooltip.defaults = {
        animation: true
    , placement: 'top'
    , selector: false
    , template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
    , trigger: 'hover'
    , title: ''
    , delay: 0
    , html: false
    }


    /* TOOLTIP NO CONFLICT
     * =================== */

    $.fn.tooltip.noConflict = function () {
        $.fn.tooltip = old
        return this
    }

}(window.jQuery);
(function ($) {
    $.fn.html5Uploader = function (options) {
        var crlf = '\r\n'; var boundary = "iloveigloo"; var dashes = "--"; var settings = { "name": "uploadedFile", "postUrl": "Upload.aspx", "onClientAbort": null, "onClientError": null, "onClientLoad": null, "onClientLoadEnd": null, "onClientLoadStart": null, "onClientProgress": null, "onServerAbort": null, "onServerError": null, "onServerLoad": null, "onServerLoadStart": null, "onServerProgress": null, "onServerReadyStateChange": null, "onSuccess": null }; if (options) { $.extend(settings, options); }
        return this.each(function (options) {
            var $this = $(this); if ($this.is("[type=\"file\"]")) { $this.bind("change", function () { var files = this.files; for (var i = 0; i < files.length; i++) { fileHandler(files[i]); } }); } else {
                $this.bind("dragenter dragover", function () { return false; }).bind("drop", function (e) {
                    var files = e.originalEvent.dataTransfer.files; for (var i = 0; i < files.length; i++) { fileHandler(files[i]); }
                    return false;
                });
            }
        }); function fileHandler(file) {
            var fileReader = new FileReader(); fileReader.onabort = function (e) { if (settings.onClientAbort) { settings.onClientAbort(e, file); } }; fileReader.onerror = function (e) { if (settings.onClientError) { settings.onClientError(e, file); } }; fileReader.onload = function (e) { if (settings.onClientLoad) { settings.onClientLoad(e, file); } }; fileReader.onloadend = function (e) { if (settings.onClientLoadEnd) { settings.onClientLoadEnd(e, file); } }; fileReader.onloadstart = function (e) { if (settings.onClientLoadStart) { settings.onClientLoadStart(e, file); } }; fileReader.onprogress = function (e) { if (settings.onClientProgress) { settings.onClientProgress(e, file); } }; fileReader.readAsDataURL(file); var xmlHttpRequest = new XMLHttpRequest(); xmlHttpRequest.upload.onabort = function (e) { if (settings.onServerAbort) { settings.onServerAbort(e, file); } }; xmlHttpRequest.upload.onerror = function (e) { if (settings.onServerError) { settings.onServerError(e, file); } }; xmlHttpRequest.upload.onload = function (e) { if (settings.onServerLoad) { settings.onServerLoad(e, file); } }; xmlHttpRequest.upload.onloadstart = function (e) { if (settings.onServerLoadStart) { settings.onServerLoadStart(e, file); } }; xmlHttpRequest.upload.onprogress = function (e) { if (settings.onServerProgress) { settings.onServerProgress(e, file); } }; xmlHttpRequest.onreadystatechange = function (e) {
                if (settings.onServerReadyStateChange) { settings.onServerReadyStateChange(e, file, xmlHttpRequest.readyState); }
                if (settings.onSuccess && xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) { settings.onSuccess(e, file, xmlHttpRequest.responseText); }
            }; xmlHttpRequest.open("https://cdn4.tgdd.vn/v2015/Scripts/desktop/V5/POST", settings.postUrl, true); if (file.getAsBinary) { var data = dashes + boundary + crlf + "Content-Disposition: form-data;" + "name=\"" + settings.name + "\";" + "filename=\"" + unescape(encodeURIComponent(file.name)) + "\"" + crlf + "Content-Type: application/octet-stream" + crlf + crlf + file.getAsBinary() + crlf + dashes + boundary + dashes; xmlHttpRequest.setRequestHeader("Content-Type", "multipart/form-data;boundary=" + boundary); xmlHttpRequest.sendAsBinary(data); } else if (window.FormData) { var formData = new FormData(); formData.append(settings.name, file); xmlHttpRequest.send(formData); }
        }
    };
})(jQuery);
jQuery.fn.center = function () {
    this.css("position", "fixed");
    this.css("top", "200px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                                                $(window).scrollLeft()) + "px");
    return this;
}
String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};
function empty(mixed_var) {
    var key;
    if (mixed_var === ""
        //  || mixed_var === 0
        //  || mixed_var === "0"
        || mixed_var === null
        || mixed_var === false
        || mixed_var === undefined
         || mixed_var === 'undefined'
        || mixed_var === 'Khách hàng'
         || mixed_var === 'null'
    ) {
        return true;
    }
    if (typeof mixed_var == 'object') {
        for (key in mixed_var) {
            if (typeof mixed_var[key] !== 'function') {
                return false;
            }
        }
        return true;
    }
    return false;
}
function checkempty(mixed_var) {
    var key;
    if (mixed_var === ""
        //  || mixed_var === 0
        //  || mixed_var === "0"
        || mixed_var === null
        || mixed_var === false
        || mixed_var === undefined
         || mixed_var === 'undefined'
         || mixed_var === 'null'
    ) {
        return "";
    }

    return mixed_var;
}
var chat_enabled = true;
var connecting = false;
var FADE_TIME = 150; // ms
var CHAT_LOAT_INVITE_TIME = 30000; // ms
var TYPING_TIMER_LENGTH = 400; // ms
var isOutOfWork = 0;
var isautochat = 0;
var isadmininvite = 0;
var isloadcommnet = 0;
var invitetime = 90;
var CHAT_ENABLED = 1;
var CHAT_INVITE = 1;
var CHAT_ISMOBILE = 0;
var welcome_msg = '';
var CHAT_BOT = 0;
var isInitUi = 0;
var isInviteChat = 0;
var CHAT_SOURCE = '';
var CHAT_IN_CMT = false;
var g_ostype = 1;//1 desktop , 2 mobile
var g_cateid = 0;
var g_productid = 0;
var g_productprice = 0;
var g_categoryname = '';
var g_groupcategoryname = '';
var g_manuid = 0;
var supporter, intervalPingPong, username, address, phone, email, gender, room, adid, userType = 'u', name, ad_name, ad_avatar, theOpposite, theAdmin, intervalRefresh, connected = false, typing = false,
    lastTypingTime, myskid, isAdmin = 0, ismininvitechat = 0, isMinimizing = 0, unreadCount = 0, tmoOnlineSupporter;

var $chatCDN = '';
var $avatarCDN = '';
var nodeJsServer = '';


var sitename = 'DienMayXanh';
var sitedomain = 'dienmayxanh.com';
var sitechaturl = 'https://www.dienmayxanh.com/chat';
var CHAT_TPL = {};
var CMD_CHAT_REQUEST = 'chat requesttgdd';
var CMD_CHAT_ONLINESUPPORTER = 'chat onlinesupporterall2018';
var CHAT_SITEID = 0;
var CHAT_DANHXUNG = 'bạn';
var CHAT_DANHXUNG_HOA = 'Bạn';
var CHAT_TPL_CLASS = '';
var CHAT_CDNV = 'v201705000042';
var CHAT_TESTTYPE = 0;
var CHAT_ISCHATING = 0;
var CHAT_ISCONNECTED = false;

var cookie_info = 'chat.info';
if ("undefined" != typeof CHAT_GL_TESTTYPE) {
    CHAT_TESTTYPE = CHAT_GL_TESTTYPE;
}


if ("undefined" != typeof CHAT_GL_CHATBOT) {
    CHAT_BOT = CHAT_GL_CHATBOT;
}

if ("undefined" != typeof CHAT_GL_SITE) {
    CHAT_SITEID = CHAT_GL_SITE;
}
if (CHAT_SITEID == 0) {
    if (location.href.indexOf('www.dienmayxanh.com') >= 0) CHAT_SITEID = 2;
    if (location.href.indexOf('www.vuivui.com') >= 0) CHAT_SITEID = 8;
    if (location.href.indexOf('www.thegioididong.com') >= 0) CHAT_SITEID = 1;
    if (location.href.indexOf('www.nhathuocankhang.com') >= 0) CHAT_SITEID = 10;

}

if ("undefined" != typeof CHAT_GL_INVITE) {
    CHAT_INVITE = CHAT_GL_INVITE;
}
if ("undefined" != typeof CHAT_GL_ENABLED) {
    CHAT_ENABLED = CHAT_GL_ENABLED;
}
var cdnChatUrl = "https://cdn.thegioididong.com/";
if (CHAT_SITEID == 1) {
    CHAT_TPL_CLASS = 'chattgdd';
    nodeJsServer = 'https://rtm.thegioididong.com/';
    sitechaturl = 'https://www.thegioididong.com/chat';
    $chatCDN = '' + cdnChatUrl + '/dmxchat';
    $avatarCDN = '' + cdnChatUrl + '/dmxchat/avatar';
    sitename = 'TheGioiDiDong';
    sitedomain = 'thegioididong.com';
    siteurldomain = 'www.thegioididong.com';
    siteshorturldomain = 'thegioididong.com';
    CMD_CHAT_REQUEST = 'chat requestall';

    CHAT_LOAT_INVITE_TIME = 10000;
}
if (CHAT_SITEID == 2) {
    CHAT_TPL_CLASS = 'chatdmx';
    sitechaturl = 'https://www.dienmayxanh.com/chat';
    // nodeJsServer = 'https://rtm.dienmayxanh.com';
    nodeJsServer = 'https://rtm.thegioididong.com/';
    $chatCDN = '' + cdnChatUrl + '/dmxchat';
    $avatarCDN = '' + cdnChatUrl + '/dmxchat/avatar';
    sitename = 'DienMayXanh';
    sitedomain = 'dienmayxanh.com';
    siteurldomain = 'www.dienmayxanh.com';
    siteshorturldomain = 'dienmayxanh.com';
    CMD_CHAT_REQUEST = 'chat requestall';

    // CMD_CHAT_ONLINESUPPORTER = 'chat onlinesupportervuivui';
    CHAT_LOAT_INVITE_TIME = 10000;
}
if (CHAT_SITEID == 10) {
    CHAT_TPL_CLASS = 'chatdmx';
    sitechaturl = 'https://www.nhathuocankhang.com/chat';
    nodeJsServer = 'https://rtm.thegioididong.com/';
    $chatCDN = '' + cdnChatUrl + '/dmxchat';
    $avatarCDN = '' + cdnChatUrl + '/dmxchat/avatar';
    sitename = 'DienMayXanh';
    sitedomain = 'nhathuocankhang.com';
    siteurldomain = 'www.nhathuocankhang.com';
    siteshorturldomain = 'nhathuocankhang.com';
    CMD_CHAT_REQUEST = 'chat requestall';


    CHAT_LOAT_INVITE_TIME = 10000;
}
if (CHAT_SITEID == 8) {
    CHAT_TPL_CLASS = 'chatvuivui';
    sitechaturl = 'https://www.vuivui.com/chat';
    nodeJsServer = 'https://rtm.vuivui.com/';
    // nodeJsServer = 'https://rtm.thegioididong.com';
    $chatCDN = '' + cdnChatUrl + '/dmxchat';
    $avatarCDN = '' + cdnChatUrl + '/dmxchat/avatar';
    sitename = 'VuiVui';
    sitedomain = 'vuivui.com';
    siteurldomain = 'www.vuivui.com';
    siteshorturldomain = 'vuivui.com';
    CMD_CHAT_REQUEST = 'chat requestall';

    CHAT_LOAT_INVITE_TIME = 10000;
}


if (CHAT_TESTTYPE == 1) {
    $chatCDN = 'http://localdienmay.com/chat/resx';
    nodeJsServer = 'http://localhost:8889/';
    sitechaturl = 'http://localdienmay.com/chat';
    sitedomain = 'localdienmay.com';
    CHAT_LOAT_INVITE_TIME = 5000;
}
//if (CHAT_TESTTYPE == 1) {
//    $chatCDN = 'http://10.142.59.50/resx';
//    nodeJsServer = 'http://10.142.59.50:8889';
//    sitechaturl = 'http://10.142.59.50/';
//    CHAT_LOAT_INVITE_TIME = 5000;
//}

if (CHAT_TESTTYPE == 2) {
    $chatCDN = 'http://beta.thegioididong.com/chat/resx';
    $avatarCDN = '' + cdnChatUrl + '/dmxchat/avatar';
    sitechaturl = 'http://beta.thegioididong.com/chat';
    CHAT_LOAT_INVITE_TIME = 5000;
}
if (CHAT_TESTTYPE == 3) {
    $chatCDN = 'http://test.thegioididong.com/chat/resx';
    $avatarCDN = '' + cdnChatUrl + '/dmxchat/avatar';
    sitechaturl = 'http://test.thegioididong.com/chat';
    CHAT_LOAT_INVITE_TIME = 5000;
}
if (CHAT_TESTTYPE == 1 || CHAT_TESTTYPE == 3) { //local

    $('head').append('<link  rel="stylesheet" type="text/css" href="' + $chatCDN + '/chatcore2017.css?t=' + CHAT_CDNV + '" />');
} else if (CHAT_TESTTYPE == 2) {//beta 
    $('head').append('<link  rel="stylesheet" type="text/css" href="' + $chatCDN + '/chatcore2017.css?t=' + CHAT_CDNV + '" />');
} else {
    $('head').append('<link  rel="stylesheet" type="text/css" href="' + cdnChatUrl + '/dmxchat/chatcore2017' + getExtcdnfilename('.css') + '" />');
}
var chat_dialog_cmt = '  <div id="chatclientcmt" ><div class="navbar navbar-inverse navbar-fixed-bottom ' + CHAT_TPL_CLASS + '"  ><div id="divmsg"></div><ul class="pages"><li class="chat page"> <div class="operator-info"  ></div><div class="chatArea"><form  onsubmit="return false;"><div id="collectdata"></div></form><form  onsubmit="return false;"><div id="chatcloseconfirm"> xxx</div></form><div id="chatrating">Cảm ơn bạn đã sử dụng dịch vụ của TGDD.<br /> </div> <ul class="messages"></ul></div><div class="chatwait"   id="chatwait"  style="  height: 22px;   " ></div><div class="chatinputarea"><form  onsubmit="return false;"><table style="width:100%;padding:0px;margin:0px"><tr> <td> <textarea   class="chatinput fe" placeholder="Nhập nội dung ' + CHAT_DANHXUNG + ' muốn gửi và nhấn Gửi"></textarea></td><td style="width: 50px; "><button id="sendchatcmt" class="chatfunctions-sendchat"     >Gửi</button> </td></tr></table></form>  </div></li></ul></div> </div>';
var  schatcmt = ' <div id="chatclientcmt">';
schatcmt = schatcmt + ' <div class="navbar navbar-inverse navbar-fixed-bottom ' + CHAT_TPL_CLASS + ' ">';
schatcmt = schatcmt + '    <div id="divmsg"></div>';
schatcmt = schatcmt + ' <ul class="pages">';
schatcmt = schatcmt + '     <li class="pagechat chat page">';
schatcmt = schatcmt + '    <div class="operator-info"></div>';
schatcmt = schatcmt + '    <div class="chatArea"> ';
schatcmt = schatcmt + '      <div id="chatrating">Cảm ơn bạn đã sử dụng dịch vụ của TGDD.<br /> </div>';
schatcmt = schatcmt + '      <ul class="messages"></ul>';
schatcmt = schatcmt + '   </div>';
schatcmt = schatcmt + '   <div class="chatwait" id="chatwait" style=" height 22px; "></div>';
schatcmt = schatcmt + '   <div class="chatinputarea">';
schatcmt = schatcmt + '    <form onsubmit="return false;">';
schatcmt = schatcmt + '    <table style="width:100%;padding:0px;margin:0px">';
schatcmt = schatcmt + ' <tr>';
schatcmt = schatcmt + ' <td>';
schatcmt = schatcmt + '     <textarea class="chatinput fe" placeholder="Nhập nội dung muốn gửi và nhấn Gửi"></textarea>';
schatcmt = schatcmt + '  </td>';
schatcmt = schatcmt + '  <td style="width: 50px; ">';
schatcmt = schatcmt + '       <button id="sendchatcmt" class="chatfunctions-sendchat">Gửi</button>';
schatcmt = schatcmt + '    </td>';
schatcmt = schatcmt + '  </tr>';
schatcmt = schatcmt + '  </table>';
schatcmt = schatcmt + '   </form>';
schatcmt = schatcmt + '  </div>';
schatcmt = schatcmt + '</li>';
schatcmt = schatcmt + '  <li class="pageclose chat page"> ';
schatcmt = schatcmt + '     <div class="operator-info"></div>   <form onsubmit="return false;"><div id="chatcloseconfirm"> xxx</div></form>';
schatcmt = schatcmt + '    </li>';
schatcmt = schatcmt + '   </ul>';
schatcmt = schatcmt + ' </div>';  
schatcmt = schatcmt + '</div>';

chat_dialog_cmt = schatcmt;

var popup_dialog = '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Bình luận của bạn đã được trả lời</h4></div><div class="modal-body" id="chat-invite-body">{%content%}</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Đóng lại</button><button type="button" class="btn btn-primary" id="review_answer">Xem lại trả lời</button><button type="button" class="btn btn-primary" data-dismiss="modal" onclick="acceptChat()" id="chat_with_admin">Chat ngay với QTV</button></div></div></div></div>';
var chat_dialogold = '<div style="clear:both;"/><div id="chatclient-back"></div>  <div id="chatclient" ><div class="navbar navbar-inverse navbar-fixed-bottom ' + CHAT_TPL_CLASS + '"  ><div id="divmsg"></div><ul class="pages"><li class="chat page"> <div class="operator-info"  ></div><div class="chatArea"><form  onsubmit="return false;"><div id="collectdata"></div></form><form  onsubmit="return false;"><div id="chatcloseconfirm"> xxx</div></form><div id="chatrating">Cảm ơn bạn đã sử dụng dịch vụ của TGDD.<br /> </div> <ul class="messages"></ul></div><div class="chatwait"   id="chatwait"  style="  height: 22px;   " ></div><div class="chatinputarea"><form  onsubmit="return false;"><table style="width:100%;padding:0px;margin:0px"><tr><td style="width: 42px; "  >  <input id="upload-image" type="file" multiple class="upload-image" itemid="0"  title="Chèn ảnh kích thước tối đa 1MB/hình" name="file" /><img id="insertimage" class="chatfunctions-insertimg"  src="' + $chatCDN + '/chenhinh_tgdd' + getExtcdnfilename('.png') + '" >  </td><td> <textarea   class="chatinput fe" placeholder="Nhập nội dung ' + CHAT_DANHXUNG + ' muốn gửi và nhấn Gửi"></textarea></td><td style="width: 50px; "><button id="sendchat" class="chatfunctions-sendchat"     >Gửi</button> </td></tr></table></form>  </div></li></ul></div><div id="chattooltipmsg"   class="minimize" onclick="maximizeChatWindow()"><div style="position: relative;"><div class="msgalert">!</div><div style="z-index: -1;top: 2px;width: 216px;right:60px;height: 45px;display: none;" id="tooltip349481" class="tooltip fade left in" role="tooltip"> <div class="tooltip-inner"></div></div><div class="operator-info-min"></div></div></div></div>';
//var chat_dialog = '<div style="clear:both;"/><div id="chatclient-back"></div>  <div id="chatclient" ><div class="navbar navbar-inverse navbar-fixed-bottom ' + CHAT_TPL_CLASS + '"  ><div id="divmsg"></div><ul class="pages"><li class="chat page"> <div class="operator-info"  ></div><div class="chatArea"><form  onsubmit="return false;"><div id="collectdata"></div></form><form  onsubmit="return false;"><div id="chatcloseconfirm"> xxx</div></form><div id="chatrating">Cảm ơn bạn đã sử dụng dịch vụ của TGDD.<br /> </div> <ul class="messages"></ul></div><div class="chatwait"   id="chatwait"  style="  height: 22px;   " ></div><div class="chatinputarea"><form  onsubmit="return false;"><table style="width:100%;padding:0px;margin:0px"><tr><td style="width: 42px; "  >  <input id="upload-image" type="file" multiple class="upload-image" itemid="0"  title="Chèn ảnh kích thước tối đa 1MB/hình" name="file" /><img id="insertimage" class="chatfunctions-insertimg"  src="' + $chatCDN + '/chenhinh_tgdd' + getExtcdnfilename('.png') + '" >  </td><td> <textarea   class="chatinput fe" placeholder="Nhập nội dung ' + CHAT_DANHXUNG + ' muốn gửi và nhấn Gửi"></textarea></td><td style="width: 50px; "><button id="sendchat" class="chatfunctions-sendchat"     >Gửi</button> </td></tr></table></form>  </div></li></ul></div><div id="chattooltipmsg"   class="minimize" onclick="maximizeChatWindow()"><div style="position: relative;"><div class="msgalert">!</div><div style="z-index: -1;top: 2px;width: 216px;right:60px;height: 45px;display: none;" id="tooltip349481" class="tooltip fade left in" role="tooltip"> <div class="tooltip-inner"></div></div><div class="operator-info-min"></div></div></div></div>';


var schatdl = '<div style="clear:both;" /><div id="chatclient-back"></div>';
schatdl = schatdl + '<div id="chatclient">';
schatdl = schatdl + ' <div class="navbar navbar-inverse navbar-fixed-bottom ' + CHAT_TPL_CLASS + '">';
schatdl = schatdl + '    <div id="divmsg"></div>';
schatdl = schatdl + '  <ul class="pages">';
schatdl = schatdl + '    <li class="pagechat chat page">';
schatdl = schatdl + '  <div class="operator-info"></div>';
schatdl = schatdl + '  <div class="chatArea">';
schatdl = schatdl + '        <div id="chatrating">Cảm ơn bạn đã sử dụng dịch vụ của TGDD.<br /> </div>';
schatdl = schatdl + '            <ul class="messages"></ul>';
schatdl = schatdl + '       </div>';
schatdl = schatdl + '  <div class="chatwait" id="chatwait" style=" height 22px; "></div>';
schatdl = schatdl + '   <div class="chatinputarea">';
schatdl = schatdl + '       <form onsubmit="return false;">';
schatdl = schatdl + '            <table style="width:100%;padding:0px;margin:0px">';
schatdl = schatdl + '            <tr>';
schatdl = schatdl + '  <td style="width: 42px; ">';
schatdl = schatdl + '        <input id="upload-image" type="file" multiple class="upload-image" itemid="0" title="Chèn ảnh kích thước tối đa 1MB/hình" name="file" /><img id="insertimage" class="chatfunctions-insertimg" src="' + $chatCDN + '/chenhinh_tgdd' + getExtcdnfilename('.png') + '">';
schatdl = schatdl + '   </td>';
schatdl = schatdl + ' <td>';
schatdl = schatdl + '  <textarea class="chatinput fe" placeholder="Nhập nội dung ' + CHAT_DANHXUNG + ' muốn gửi và nhấn Gửi"></textarea>';
schatdl = schatdl + '  </td>';
schatdl = schatdl + '  <td style="width: 50px; "><button id="sendchat" class="chatfunctions-sendchat">Gửi</button> </td>';
schatdl = schatdl + ' </tr>';
schatdl = schatdl + '    </table>';
schatdl = schatdl + '    </form>';
schatdl = schatdl + '  </div>';
schatdl = schatdl + ' </li>';
schatdl = schatdl + '  <li class="pagecollect page">';
schatdl = schatdl + '   <div class="operator-info"></div> <form onsubmit="return false;"><div id="collectdata"></div></form>';
schatdl = schatdl + '  </li>';
schatdl = schatdl + '   <li class="pageclose  page">';
schatdl = schatdl + '   <div class="operator-info"></div>   <form onsubmit="return false;"><div id="chatcloseconfirm"> xxx</div></form>';
schatdl = schatdl + '  </li>';
schatdl = schatdl + '   </ul>';
schatdl = schatdl + ' </div><div id="chattooltipmsg" class="minimize" onclick="maximizeChatWindow()">';
schatdl = schatdl + ' <div style="position: relative;">';
schatdl = schatdl + '    <div class="msgalert">!</div>';
schatdl = schatdl + '     <div style="z-index: -1;top: 2px;width: 216px;right:60px;height: 45px;display: none;" id="tooltip349481" class="tooltip fade left in" role="tooltip"> <div class="tooltip-inner"></div></div>';
schatdl = schatdl + '        <div class="operator-info-min"></div>';
schatdl = schatdl + '      </div>';
schatdl = schatdl + '    </div>';
schatdl = schatdl + '</div>';
var chat_dialog = schatdl;
var fullslot1 = '<div id="chat_fullslot">Hiện tại tất cả Tư vấn viên đều đang bận. <br />Nếu ' + CHAT_DANHXUNG + ' cần tư vấn ngay, vui lòng để lại tên và số điện thoại, chúng tôi sẽ hỗ trợ bằng cách gọi cho ' + CHAT_DANHXUNG + '.<div><input type="text" id="chat_callme_fullname" name="chat_callme_fullname" placeholder="Vui lòng nhập tên" /></div><div><input type="text" id="chat_callme_phone" name="chat_callme_phone" placeholder="Vui lòng nhập số điện thoại" /></div><div><input type="button" value="Gọi cho tôi" onclick="SendCallMeInfo()" /></div></div>';
var fullslot = '<div id="chat_fullslot">Hiện tại tất cả Tư vấn viên đều đang bận. <br />' + CHAT_DANHXUNG + ' vui lòng chờ trong ít phút.</div>';
var fullslotOutOfWork = '<div id="chat_fullslot">Cảm ơn ' + CHAT_DANHXUNG + ' đã quan tâm đến mục "Tư vấn" của ' + sitename + '. Hệ thống phục vụ từ 7 giờ đến 22 giờ, kính mong ' + CHAT_DANHXUNG + ' quay lại vào thời gian trên để được phục vụ tốt nhất.<br /> Trân trọng!</div>';
var collecthtml = ' <div class="collectsub"> <p>Mời ' + CHAT_DANHXUNG + ' nhập số điện thoại để chúng tôi hỗ trợ tốt hơn</p> <div><select id="txtchat_gender" name="txtchat_gender"><option value="">Giới tính</option><option value="1">Anh</option> <option  value="2">Chị</option></select><input type="text" id="txtchat_fullname" name="txtchat_fullname" placeholder="Tên của ' + CHAT_DANHXUNG + '" />  </div> <div class="valifullname"></div>   <div>  <input type="text" id="txtchat_phone" name="txtchat_phone" placeholder="Số điện thoại (bắt buộc)" onkeypress="return numbersOnly(this, event);" maxlength="11"/>  </div><div class="valiphone"></div> <div style="display:">  <input type="text" id="txtchat_email" name="txtchat_email" placeholder="Email (không bắt buộc)" />  </div> <div class="valiemail"></div><div id="inimsg"></div>   <div> <div class="startbutton" value="" onclick="iniChat()" ><span>Bắt đầu Chat</span><p style="display:none">Lời mời chat còn hiệu lực trong <span id="invitetime"></span> giây</p></div></div><div><span style="color:#9d9d9d">Cam kết bảo mật thông tin</span></div></div> ';
var notifychatmsg = '<div  id="notifychatmsg" class="' + CHAT_TPL_CLASS + '"> </div> ';
var notifychatmsgmin = '<div  id="notifychatmsgmin"> </div> ';
var notifychatnewmsg = '<div  id="notifychatnewmsg"> </div> ';
var chatclientbackfull = '<div id="chatclient-backfull"></div> ';
var auhtmlinvitechat = "<div class=\"notifyChat2  \"   ><a   href=\"javascript:void(0)\" onclick=\"requestChatAuto('');\"><img class=\"notifyChat2img\" src=\"" + $chatCDN + "/iconinvitechat_" + CHAT_SITEID + "" + getExtcdnfilename('.png') + "\"></a><a class=\"btnRqChat\"  href=\"javascript:void(0)\" onclick=\"requestChatAuto('');\">Hỗ trợ online</a></div></div>";
if (CHAT_SITEID == 8) {
    auhtmlinvitechat = "<div class=\"notifyChat2  \"  style=\"width: 105px;\" ><a   href=\"javascript:void(0)\" onclick=\"requestChatAuto('');\"><img class=\"notifyChat2img\" src=\"" + $chatCDN + "/iconinvitechat_" + CHAT_SITEID + "" + getExtcdnfilename('.png') + "\"></a><a class=\"btnRqChat\"  style=\"width: 59px;\" href=\"javascript:void(0)\" onclick=\"requestChatAuto('');\">Chat với tư vấn viên</a></div></div>";

}
var auhtmlinvitechatmini = "<div class=\"notifyChat2  \"   ><a class=\"btnRqChat\"   href=\"javascript:void(0)\" onclick=\"requestChatAuto('');\"><img  class=\"notifyChat2img\" src=\"" + $chatCDN + "/iconchatdmx" + getExtcdnfilename('.png') + "\"></a></div></div>";




var socket = io(nodeJsServer, { path: '/socket.io' });
var chat_commentid = 0;
var $window = $(window), $messages, $messagescomment, $inputMessage, $minimizePage, $chatPage;
var $messagescmt, $messagescommentcmt, $inputMessagecmt, $minimizePagecmt, $chatPagecmt;

var lastmgsuser = 0;
function textAreaAdjust(o) {
    //o.style.height = "1px";
    //o.style.height = (25 + o.scrollHeight) + "px";
}
function h(e) {
    var inputheight = $('#chatclient .chatinputarea').height();
    if (inputheight < 79) {
        $(e).css({ 'height': 'auto', 'overflow-y': 'scroll' }).height(e.scrollHeight);
        resizeChatCode();
    }
}
function getGender(gd) {
    if (gd) {
        if (gd == 1) return 'Anh ';
        if (gd == 2) return 'Chị ';
    }
    return 'Bạn';

}
function deletecookiechat() {
    chatSetCookie('chat.username', '', -1, "www." + sitedomain);
    chatSetCookie('chat.username', '', -1, "." + sitedomain);
    chatSetCookie('chat.username', '', -1, "rtm." + sitedomain);
    chatSetCookie(cookie_info, '', 365, "." + sitedomain);
    chatSetCookie('chat.username', '', 365, "." + sitedomain);
    alert('daxoa ,cần reload lại trang');
}

function getExtcdnfilename(ext) {
    if (CHAT_TESTTYPE == 1 || CHAT_TESTTYPE == 2) {
        return '' + ext + '?v=' + CHAT_CDNV;
    } else {

        return '.' + CHAT_CDNV + '' + ext;
    }
}
function showchatpagecmt(page) {

    $('#chatclientcmt .pages .page').hide();
    $('#chatclientcmt .pages .page' + page).show();
    $('#chatclientcmt .navbar').show();
    $('#chatclientcmt .pages').show();
    if (page == 'chat') {

        $('#notifychatmsg').hide();
        $('#chatclientcmt .operator-info').show();
        $('#chatclientcmt .messages').show(); 
        $('#chatclientcmt .chatinput').show();
        $messagescmt.show();
        $('#chatclientcmt .chatinputarea').show();
        $("#chatclientcmt .chatfunctionscontrainer").show();
        $('#chatclientcmt .titlechat .c').show();
        $("#chatclientcmt .operator-info").show();
        addChatInfoMessageCMT();
        getMessageComment();
        $inputMessagecmt.focus();
    }
    if (page == 'collect') {
       



    }
    if (page == 'close') {
        var endchatpro = '<div class="continuechat"  onclick="continueChatCMT()">&lt; Quay lại chat</div><div class="operator-info-endchat"><p  class="endchatmsg">Mời ' + getGender(gender) + ' đánh giá chất lượng tư vấn hỗ trợ</p> <div  id="divchatrating"><div><p  ><textarea rows="4" cols="50" class="moreinfo" id="chat_moremsg" style="display:none" placeholder="Nhận xét về việc tư vấn (không bắt buộc)"></textarea> <input type="button" class="btn btn-sm btn-warning btnstopbad" value="Không hài lòng" onclick="rateBadCMT()" /> <input type="button" class="btn btn-primary btnstopgood" value="Hài lòng" onclick="rateGoodCMT()" /> </p> <p>hoặc</p><span onclick="rateIngoreCMT()" class="rateingrelink">Đóng chat ngay</span> </div>';
        endchatpro = endchatpro.replace('{ad_name}', theOpposite);
        endchatpro = endchatpro.replace('{ad_avatar}', ad_avatar);
        $('#chatclientcmt #chatcloseconfirm').html(endchatpro);

    }
}

function showchatpage(page) {
  
    $('#chatclient .pages .page').hide();
    $('#chatclient .pages .page' + page).show();
    $('#chatclient .navbar').show();
    $('#chatclient .pages').show();
    if (page == 'chat') {
      
        $('#notifychatmsg').hide();
        $('#chatclient .operator-info').show();
        $('#chatclient .messages').show();
       
        $('#chatclient  .chatinput').show();
        $messages.show();
        $('#chatclient .chatinputarea').show();
        $("#chatclient .chatfunctionscontrainer").show();
        $('#chatclient .titlechat .c').show();
        $("#chatclient .operator-info").show();
        addChatInfoMessage();
        getMessageComment(); 
        //$inputMessage.focus();
    }
    if (page == 'collect') {
        LoadUserInfo();
        $('#chatclient #collectdata').show(); 
        $('#txtchat_fullname').val(name);
        $('#txtchat_phone').val(phone);
        $('#txtchat_email').val(email);
        $('#txtchat_gender').val(gender);
        $('#chatclient #collectdata').show();
       
        
       

    }
    if (page == 'close') {
        var endchatpro = '<div class="continuechat"  onclick="continueChat()">&lt; Quay lại chat</div><div class="operator-info-endchat"><p  class="endchatmsg">Mời ' + CHAT_DANHXUNG + ' đánh giá chất lượng tư vấn hỗ trợ</p><div class="avatarto"><img src="{ad_avatar}"/></div><span>{ad_name}</span><div  id="divchatrating"><div><p  ><textarea rows="4" cols="50" class="moreinfo" id="chat_moremsg" style="display:none" placeholder="Nhận xét về việc tư vấn (không bắt buộc)"></textarea> <input type="button" class="btn btn-sm btn-warning btnstopbad" value="Không hài lòng" onclick="rateBad()" /> <input type="button" class="btn btn-primary btnstopgood" value="Hài lòng" onclick="rateGood()" /> </p> <p>hoặc</p><span onclick="rateIngore()" class="rateingrelink">Đóng chat ngay</span> </div>';
        endchatpro = endchatpro.replace('{ad_name}', theOpposite);
        endchatpro = endchatpro.replace('{ad_avatar}', ad_avatar);
        $('#chatclient #chatcloseconfirm').html(endchatpro);
        
    }
}
function enablechat(isshow) {
    if (isshow == 1) {

        $inputMessage.removeAttr("disabled");
        $inputMessagecmt.removeAttr("disabled");
        $inputMessage.attr('placeholder', 'Nhập nội dung ' + CHAT_DANHXUNG + ' muốn gửi và nhấn Gửi');
        $inputMessagecmt.attr('placeholder', 'Nhập nội dung ' + CHAT_DANHXUNG + ' muốn gửi và nhấn Gửi');
    } else {

        $inputMessage.attr("disabled", "disabled");
        $inputMessagecmt.attr("disabled", "disabled");
        $inputMessage.attr('placeholder', 'Kết nối bị gián đoạn, vui lòng đợi trong giây lát hoặc tải lại trang');
        $inputMessagecmt.attr('placeholder', 'Kết nối bị gián đoạn, vui lòng đợi trong giây lát hoặc tải lại trang');
    }

}

function shochatwmsg(isshow, msg) {
    if (isshow == 1) {
        $('#chatclient .chatwait').show();
        $('#chatclient .chatwait').html(msg);
        $('#chatclientcmt .chatwait').show();
        $('#chatclientcmt .chatwait').html(msg);


    } else {
        $('#chatclient .chatwait').hide();
        $('#chatclient .chatwait').html("");
       $('#chatclientcmt .chatwait').hide();
        $('#chatclientcmt .chatwait').html("");

    }
    resizeChatCode();
    resizeChatCodeCMT();
}
function reseth() {
    $('#chatclient .chatinputarea').height(50);
    $('#chatclient .chatinputarea .chatinput ').height(36);
    resizeChatCode();
    $('#chatclient .chatinputarea').css({ 'height': 'auto' });
}
$(function () {
    resizeChat();
});
function numbersOnly(n, t) { var i = String.fromCharCode(t.charCode), r = /\./.test(n.value); return t.charCode === 0 || /\d/.test(i) || /\./.test(i) && !r }
function changeTitlePage(title) {
    document.title = title;
}
function checkvisible(item) {
    if ($(item).is(":visible")) {
        return true;
    }
    return false;
}

function enablechatinput() {
    enablechat(1);
  
}

function CheckIsMobile() {
    if ($(this).width() <= 450) {
        return true;
    } else {
        return false;
    }

}
function resizeChatCode() {
    var $navbar = $('#chatclient  navbar ');

    var narhei = 500;
    if ($(this).width() <= 450) {
        CHAT_ISMOBILE = 1;
        narhei = $(this).height() / 2;
    } else {
        CHAT_ISMOBILE = 0;
        narhei = 500;
    }
    $navbar.height(narhei);
    var $content = $('#chatclient .messages');
    var chatwait = $('#chatclient #chatwait');
    var $chatArea = $('#chatclient .chatArea');
    //
    var inputheight = $('#chatclient .chatinputarea').height();
    var operatorheight = 40;//$('#chatclient .operator-info ').height(); 
    // var wheight = $(this).height();
    // var navheight = wheight - 70;
    var chatheight = narhei - inputheight - operatorheight - 5;
    if (checkvisible('#chatclient #chatwait')) {
        chatheight = narhei - inputheight - operatorheight - 5 - 22;
    }
    $('#chatclient #collectdata').height(narhei);
    $('#chatclient .pages').height(narhei);
    $('#chatclient .page').height(narhei);
    $content.height(chatheight);
    $chatArea.height(chatheight);
    if (checkvisible('#collectdata')) {
        $('#chatclient  .operator-info .linkimg img').hide();
        $('#chatclient  .operator-info .linkimg').css('width', '2px');
    } else {
        $('#chatclient  .operator-info .linkimg img').show();
        $('#chatclient  .operator-info .linkimg').css('width', '36px');
    }

    if (checkvisible('#chatclient  navbar')) {
        $('#notifychatmsg').hide();
    }
    
    if (CHAT_ISMOBILE == 1) {//move phai nâng cao
        fnMoveChat('#chatclient  .navbar', 0, 0, 0, 2);
        if (CHAT_SITEID == 2) {
            fnMoveChat('#notifychatmsg', 56, 2, 0, 0);
            fnMoveChat('#chattooltipmsg', 56, 2, 0, 0);
            $('#notifychatmsg  .notifyChat2 ').css('opacity', '0.8');
            opacity: 0.5;
        } else if (CHAT_SITEID == 8) {
            fnMoveChat('#notifychatmsg', 56, 2, 0, 0);
            fnMoveChat('#chattooltipmsg', 56, 2, 0, 0);
            $('#notifychatmsg  .notifyChat2 ').css('opacity', '0.8');
            opacity: 0.5;
        } else {

            fnMoveChat('#notifychatmsg', 5, 2, 0, 0);
            fnMoveChat('#chattooltipmsg', 5, 2, 0, 0);
        }

    } else {
        fnMoveChat('#chatclient .navbar', 2, 0, 0, 0);//move phai nâng cao
        fnMoveChat('#notifychatmsg', 100, 0, 2, 0);
        fnMoveChat('#chattooltipmsg', 100, 0, 2, 0);
    }

}
function resizeChatCodeCMT() {
    var $navbar = $('#chatclientcmt  navbar ');

    var narhei = 500;
    if ($(this).width() <= 450) {
        CHAT_ISMOBILE = 1;
        narhei = 400;// $(this).height() / 2;
    } else {
        CHAT_ISMOBILE = 0;
        narhei = 500;
    }
    $navbar.height(narhei);
    var $content = $('#chatclientcmt .messages');
    var chatwait = $('#chatclientcmt #chatwait');
    var $chatArea = $('#chatclientcmt .chatArea');
    //
    var inputheight = $('#chatclientcmt .chatinputarea').height();
    var operatorheight = 40;

    var chatheight = narhei - inputheight - operatorheight - 5;
    if (checkvisible('#chatclientcmt #chatwait')) {
        chatheight = narhei - inputheight - operatorheight - 5 - 22;
    }
    $('#chatclientcmt #collectdata').height(narhei);
    $('#chatclientcmt .pages').height(narhei);
    $('#chatclientcmt .page').height(narhei);
    $('#chatclientcmt .pagechat').height(narhei);
    $('#chatclientcmt .pageclose').height(narhei);
    $('#chatclientcmt #chatcloseconfirm').height(narhei);
    $content.height(chatheight);
    $chatArea.height(chatheight);
    if (checkvisible('#collectdata')) {
        $('#chatclientcmt  .operator-info .linkimg img').hide();
        $('#chatclientcmt  .operator-info .linkimg').css('width', '2px');
    } else {
        $('#chatclientcmt  .operator-info .linkimg img').show();
        $('#chatclientcmt  .operator-info .linkimg').css('width', '36px');
    }

    if (checkvisible('#chatclientcmt  navbar')) {
        $('#notifychatmsg').hide();
    }
    //fnMoveChat(item, bottom, left, right, top)

    if (CHAT_ISMOBILE == 1) {//move phai nâng cao
        fnMoveChat('#chatclientcmt  .navbar', 0, 0, 0, 2);
        if (CHAT_SITEID == 2) {
            fnMoveChat('#notifychatmsg', 56, 2, 0, 0);
            fnMoveChat('#chattooltipmsg', 56, 2, 0, 0);
            $('#notifychatmsg  .notifyChat2 ').css('opacity', '0.8');
            opacity: 0.5;
        } else if (CHAT_SITEID == 8) {
            fnMoveChat('#notifychatmsg', 56, 2, 0, 0);
            fnMoveChat('#chattooltipmsg', 56, 2, 0, 0);
            $('#notifychatmsg  .notifyChat2 ').css('opacity', '0.8');
            opacity: 0.5;
        } else {

            fnMoveChat('#notifychatmsg', 5, 2, 0, 0);
            fnMoveChat('#chattooltipmsg', 5, 2, 0, 0);
        }

    } else {
        var left = $(this).width() / 2 - 350
        fnMoveChat('#chatclientcmt .navbar', 0, left, 0, 20);//move phai nâng cao

    }

}
function resizeChat() {

    var $window = $(window).on('resize', function () {
        resizeChatCode();
        $('#notifychatnewmsg').center();
    }).trigger('resize');

}

var runTimmer = 0;
function startTimer(seconds) {
    function tick() {
        if (runTimmer == 1) {
            seconds--;
            $('.startbutton p').show();
            $('#invitetime').html(seconds);
            if (seconds > 0) {
                setTimeout(tick, 1000);
            } else {
                ChatStopAll();
                $('#notifychatmsg').hide();
                runTimmer = 0;
                return;
            }
        }
    }
    tick();
}
 
function SetAutoInfo() {

    ad_avatar = $chatCDN + "/iconchattgdd" + getExtcdnfilename('.png') + "";
    var chat_dialog = '<ul><li><div href="javascript:void();" class="linkimg"><img src="{ad_avatar}" width="56" class="avatar"/></div></li><li style="width:148px"><span class="operator-role"  >Chat với Tư Vấn Bán Hàng <span class="operator-name">{ad_name}</span></span></li><li class="last"> <a  href="javascript:void(0)" onclick="minizeChatWindow()"  data-toggle="tooltip" data-placement="bottom" title="Thu gọn"><img src="' + $chatCDN + '/minimize_' + CHAT_SITEID + '' + getExtcdnfilename('.png') + '" /></a><a class="stopchat"  data-toggle="tooltip" data-placement="bottom" title="Đóng" href="javascript:void(0)" onclick="closeAndStopChat()"><img class=\"closeAndStopChatimg\" src="' + $chatCDN + '/iconstop_' + CHAT_SITEID + '' + getExtcdnfilename('.png') + '" /></a></li></ul>';
    chat_dialog = chat_dialog.replace('{name}', "" + CHAT_DANHXUNG_HOA + " đang chat với Tư Vấn Bán Hàng ");

    chat_dialog = chat_dialog.replace('{ad_name}', '');
    chat_dialog = chat_dialog.replace('{ad_avatar}', ad_avatar);
    $('.operator-info').html(chat_dialog);

    $('.operator-info-min').html('<img src="' + ad_avatar + '" class="avatar"/>');
    $('.chat .operator-info').css('height', '80px;');


    collecthtml = collecthtml.replace('{ad_name}', '');
    collecthtml = collecthtml.replace('{ad_avatar}', ad_avatar);
    $('#chatclient #collectdata').html(collecthtml);
    //loadTitleChatInfoToUI(null);
    LoadUserInfo();
    if (!empty(name)) {
        $('#txtchat_fullname').val(name);
    }

}
function fnMoveChat_(bottomposchat, bottompostinvite, bottomposminimin) {
    $('.navbar').css('bottom', bottomposchat + 'px');
    $('#notifychatmsg').css('bottom', bottompostinvite + 'px');
    $('#chattooltipmsg').css('bottom', bottomposminimin + 'px');
}
function fnMoveChat(item, bottom, left, right, top) {
    if (bottom > 0) {
        $(item).css('bottom', bottom + 'px');
        $(item).css('top', 'auto');
    }
    if (top > 0) {
        $(item).css('bottom', 'auto');
        $(item).css('top', top + 'px');
    }
    if (left > 0) {
        $(item).css('right', 'auto');
        $(item).css('left', left + 'px');
    }
    if (right > 0) {
        $(item).css('right', right + 'px');
        $(item).css('left', 'auto');
    }

}
function showChatPanel() {
    requestChatAuto('');
}
function fnViewInviteChat() {

    if (checkvisible('#chattooltipmsg .operator-info-min')) {
        return;
    }
    if (checkvisible('#notifychatmsg') && checkvisible('#notifychatmsg .cmtnotifyChat')) {
        return;
    }

    var ismininvitechatCookie = chatGetCookie('chat.ismininvitechat');
    if (ismininvitechatCookie)
        ismininvitechat = ismininvitechatCookie;
    if (!ismininvitechat) {
        if (!checkvisible('#notifychatmsg')) {
            $('#notifychatmsg').show();
            $('#notifychatmsgmin').hide();
            $('#notifychatmsg').html(auhtmlinvitechat);
            $('#notifychatmsg').focus();
        }
    } else {
        if (!checkvisible('#notifychatmsgmin')) {
            $('#notifychatmsg').hide();
            $('#notifychatmsgmin').show();
            $('#notifychatmsgmin').html(auhtmlinvitechatmini);
            $('#notifychatmsgmin').focus();
        }

    }

    resizeChatCode();

}


function HideInviteChat() {
    chatSetCookie('chat.notifychatmsg', '', 365, "." + sitedomain);
    $('#notifychatmsg').hide();
    $('#notifychatmsgmin').hide();
    if (CHAT_SITEID == 1) {

    }

    //DMX
    if (CHAT_SITEID == 2) {
        $('.buychat').hide();

    }
    //VUIVUI
    if (CHAT_SITEID == 8) {
        $('#chatnow').hide();

    }

}
function ViewInviteChat(list) {
    if (CHAT_INVITE != 1) return;
    if (CHAT_ENABLED != 1) return;
    if (CHAT_IN_CMT== 1) return;
    isOutOfWork = CheckOutOfWork();
    if (isOutOfWork == 0) {

        //TGDD
        if (CHAT_SITEID == 1) {
            if (CHAT_ENABLED == 1) {
                fnViewInviteChat();
            } else {

                if ("undefined" != typeof GL_CATEGORYID && "undefined" != typeof product) {
                    if (GL_CATEGORYID == 522 || GL_CATEGORYID == 44 || product.url.indexOf('iphone') > 0) {
                        fnViewInviteChat();
                    }
                } else {
                    var listurl = 'https://www.thegioididong.com/phu-kien/,https://www.thegioididong.com/may-tinh-bang/,https://www.thegioididong.com/laptop/,https://www.thegioididong.com/dtdd-apple-iphone/,';

                    if (listurl.indexOf(location.href) >= 0) {

                        fnViewInviteChat();
                    }
                }
            }
        }

        //DMX
        if (CHAT_SITEID == 2) {

            if ("undefined" != typeof CHAT_ENABLED) {
                if (CHAT_ENABLED == 1) {
                    fnViewInviteChat();
                    $('.buychat').show();
                }
            } else {
                fnViewInviteChat();
                $('.buychat').show();
            }


        }
        //VUIVUI
        if (CHAT_SITEID == 8) {


            if ("undefined" != typeof CHAT_ENABLED) {
                if (CHAT_ENABLED == 1) {
                    $('#chatnow').show();
                    fnViewInviteChat();
                }
            } else {
                $('#chatnow').show();
                fnViewInviteChat();
            }

        }
    }

}
function CheckOutOfWork() {


    // (Áp dụng từ 0 giờ ngày 15/2 đến hết 24 giờ ngày 20/2)

    var d1 = new Date('2018-02-15 24:00:00').getTime();
    var d2 = new Date('2018-02-18 24:00:00').getTime();
    var d = new Date().getTime();

    if (d >= d1 && d <= d2) {
        return 1;
    }
    var currentHour = new Date().getHours();
    var currentMinutes = new Date().getMinutes();
    if (currentHour >= 22 && currentHour <= 24) {
        isOutOfWork = 1;
    }
    if (currentHour >= 0 && currentHour <= 7) {
        if (currentHour < 7) {
            isOutOfWork = 1;
        } else {

            if (currentMinutes > 30) {
                isOutOfWork = 0;
            }
            else {
                isOutOfWork = 1;
            }
        }
    }
    return isOutOfWork;
}

function loadTitleChatInfoToUI(data) {
    if (data) {
        if (!data.ava)
            ad_avatar = $avatarCDN + '/nopic' + getExtcdnfilename('.png') + '';
        else
            ad_avatar = $avatarCDN + '/' + data.ava;

        if (empty(name)) name = 'Khách hàng';
        if (empty(theOpposite)) theOpposite = 'Tư Vấn';
        var endchatpro = '<div class="continuechat"><input type="button"  value="< Quay lại chat" onclick="continueChat()" /></div><div class="operator-info-endchat"><p  class="endchatmsg">Mời ' + CHAT_DANHXUNG + ' đánh giá chất lượng tư vấn hỗ trợ</p><div class="avatarto"><img src="{ad_avatar}"/></div><span>{ad_name}</span><div  id="divchatrating"><div><p  ><textarea rows="4" cols="50" class="moreinfo" id="chat_moremsg" style="display:none" placeholder="Nhận xét về việc tư vấn (không bắt buộc)"></textarea> <input type="button" class="btn btn-sm btn-warning btnstopbad" value="Không hài lòng" onclick="rateBad()" /> <input type="button" class="btn btn-primary btnstopgood" value="Hài lòng" onclick="rateGood()" /> </p> <p>hoặc</p><span onclick="rateIngore()" class="rateingrelink">Đóng chat ngay</span> </div>';
        endchatpro = endchatpro.replace('{ad_name}', theOpposite);
        endchatpro = endchatpro.replace('{ad_avatar}', ad_avatar);
        $('#chatclient #chatcloseconfirm').html(endchatpro);
        var chat_dialog = '<ul><li><div href="javascript:void();" class="linkimg"><img src="{ad_avatar}" width="56" class="avatar"/></div></li><li style="width:160px"><span class="operator-role"  >Chat với Tư Vấn Bán Hàng <span class="operator-name">{ad_name}</span></span></li><li class="last"> <a  href="javascript:void(0)" onclick="minizeChatWindow()"  data-toggle="tooltip" data-placement="bottom" title="Thu gọn"><img src="' + $chatCDN + '/minimize_' + CHAT_SITEID + '' + getExtcdnfilename('.png') + '" /></a><a class="stopchat"  data-toggle="tooltip" data-placement="bottom" title="Đóng" href="javascript:void(0)" onclick="closeAndStopChat()"><img src="' + $chatCDN + '/iconstop_' + CHAT_SITEID + '' + getExtcdnfilename('.png') + '" /></a></li></ul>';


        // var chat_dialog = '<ul><li><div href="javascript:void();" class="linkimg"><img src="{ad_avatar}" width="56" class="avatar"/></div></li><li style="width:170px"><span class="operator-role"  >Chat với Tư Vấn Bán Hàng <span class="operator-name">{ad_name}</span></span></li><li class="last"><a class="stopchat" href="javascript:void(0)" onclick="minizeChatWindow()"><img src="' + $chatCDN + '/minichat' + getExtcdnfilename('.png') + '" /></a></li></ul>';
        chat_dialog = chat_dialog.replace('{name}', "" + CHAT_DANHXUNG_HOA + " đang chat với Tư Vấn Bán Hàng ");
        chat_dialog = chat_dialog.replace('{ad_name}', data.fnm);
        chat_dialog = chat_dialog.replace('{ad_avatar}', ad_avatar);
        $('#chatclient .operator-info').html(chat_dialog);
        $('#chatclient .operator-info-endchat').html('<div  ><div  class="endchatmsg"><span class="msg">Mời ' + CHAT_DANHXUNG + ' <span class="namecmt">' + name + '</span> đánh giá chất lượng tư vấn hỗ trợ?</span><img src="' + ad_avatar + '" class="avatar"/><span class="operator-name">' + theOpposite + '</span></div><div  id="divchatrating"><div><img  class="hailong"   onclick="rateGood()" src="' + $chatCDN + '/hailong' + getExtcdnfilename('.png') + '" /> <img  src="' + $chatCDN + '/khonghailong' + getExtcdnfilename('.png') + '" class="khonghailong" onclick="rateBad()" /></div><div><span onclick="rateIngore()" class="rateingrelink">Đóng chat ngay</span></div></div>');

        $('#chatclient .operator-info-min').html('<img src="' + ad_avatar + '" class="avatar"/>');
        $('#chatclient .chat .operator-info').css('height', '80px;');


        collecthtml = collecthtml.replace('{ad_name}', data.fnm);
        collecthtml = collecthtml.replace('{ad_avatar}', ad_avatar);
        $('#chatclient #collectdata').html(collecthtml);
    }
    //comment

    var chat_dialogcmt = '<ul><li>Chào ' + getGender(gender) + '<span  class="namecmt">' + name + '</span>. Chúc ' + getGender(gender) + ' một ngày tốt lành! </li><li class="last"> <a class="stopchat"   title="Đóng" href="javascript:void(0)" onclick="closeAndStopChatCMT()"><img class=\"closeAndStopChatimg\" src="' + $chatCDN + '/iconstopcmt_' + CHAT_SITEID + '' + getExtcdnfilename('.png') + '" /></a></li></ul>';
    $('#chatclientcmt .operator-info').html(chat_dialogcmt);


}
function prepairUICMT() {

    if ($('#chatclientcmt').length > 0) return;

    LoadUserInfo();
    var chatusername = chatGetCookie('chat.username');
    if (chatusername) {
        if (chatusername.indexOf('@') >= 0 && CHAT_BOT == 0) {

            return;
        }
    }


    // 
    $('body').append($(chat_dialog_cmt));


    $messagescommentcmt = $('#chatclientcmt .messagescomment'); // Messages area
    $messagescmt = $('#chatclientcmt .messages'); // Messages area
    $inputMessagecmt = $('#chatclientcmt .chatinput'); // Input message input box
    $minimizePagecmt = $('#chatclientcmt .minimize'); // The login page
    $chatPagecmt = $('#chatclientcmt .navbar'); // The chatroom page
    loadTitleChatInfoToUI(null);
    $inputMessagecmt.on('input', function () {
        CHAT_IN_CMT = true;
        updateTyping();
    });

    // Focus input when clicking on the message input's border
    $inputMessagecmt.click(function () {
        $inputMessagecmt.focus();
        CHAT_IN_CMT = true;
    });




    $('#sendchatcmt').click(function (event) {
        CHAT_IN_CMT = true;
        sendMessage();
        socket.emit('stop typing');
        typing = false;

    });
    $inputMessagecmt.on('keyup', function (event) {

        // textAreaAdjust(this);
        // When the client hits ENTER on their keyboard
        if (event.which === 13) {
            sendMessage();
            socket.emit('stop typing');
            typing = false;

        }
    }); 

    if (empty(name) || empty(phone) ||  (gender<=0)) {


        enablechat(0);
       

    } else {
        enablechat(1);
        
    }


    $('#chatclientcmt .chatinputarea').show();
    $('#chatclientcmt .chatinput').show();

    if (empty(name) || empty(phone) ||  (gender<=0)) {


       


    } else {
        

    }


    resizeChatCodeCMT();

}
function prepairUI() {


    isOutOfWork = CheckOutOfWork();
    if (isOutOfWork == 1) return;
    LoadUserInfo();
    var isAdminCookie = chatGetCookie('chat.isadmin');
    var notifychatmsgdiv = chatGetCookie('chat.notifychatmsg');
    if (isAdminCookie)
        isAdmin = isAdminCookie;
    isAdmin = 1;
    if (isInitUi == 1) return;
    isInitUi = 1;
    var isMinimizingCookie = chatGetCookie('chat.isminimize');
    if (isMinimizingCookie)
        isMinimizing = isMinimizingCookie;

    var chatusername = chatGetCookie('chat.username');
    if (chatusername) {
        if (chatusername.indexOf('@') >= 0 && CHAT_BOT == 0) {

            return;
        }
    }

    var unreadCountCookie = chatGetCookie('chat.unread');
    if (unreadCountCookie)
        unreadCount = unreadCountCookie;
    $('body').append($(notifychatmsg));
    $('body').append($(notifychatmsgmin));
    $('body').append($(notifychatnewmsg));
    $('body').append($(popup_dialog));
    $('body').append($(chat_dialog));
    $('body').append($(chatclientbackfull));


    if ("undefined" != typeof document.categoryId) g_cateid = document.categoryId;
    if ("undefined" != typeof categoryID) g_cateid = categoryID;

    if ("undefined" != typeof GL_CATEGORYID) g_cateid = GL_CATEGORYID;
    if ("undefined" != typeof GL_MANUFACTUREID) g_manuid = GL_MANUFACTUREID;
    if ("undefined" != typeof GL_PRODUCTID) g_productid = GL_PRODUCTID;
    if ("undefined" != typeof GL_PRODUCTPRICE) g_productprice = GL_PRODUCTPRICE;

    if ("undefined" != typeof GL_GROUPCATEGORYNAME) g_groupcategoryname = GL_GROUPCATEGORYNAME;
    if ("undefined" != typeof GL_CATEGORYNAME) g_categoryname = GL_CATEGORYNAME;


    if ("undefined" != typeof ProductID) g_productid = ProductID;


    $messagescomment = $('#chatclient .messagescomment'); // Messages area
    $messages = $('#chatclient .messages'); // Messages area
    $inputMessage = $('#chatclient .chatinput'); // Input message input box
    $minimizePage = $('#chatclient .minimize'); // The login page
    $chatPage = $('#chatclient .navbar'); // The chatroom page

    $inputMessage.on('input', function () {
        h(this);
        updateTyping();
    });

    // Focus input when clicking on the message input's border
    $inputMessage.click(function () {
        $inputMessage.focus();
    });




    $('#sendchat').click(function (event) {
        sendMessage();
        socket.emit('stop typing');
        typing = false;
    });
    $inputMessage.on('keyup', function (event) {
        h(this);
        // textAreaAdjust(this);
        // When the client hits ENTER on their keyboard
        if (event.which === 13) {
            sendMessage();
            socket.emit('stop typing');
            typing = false;

        }
    });


    //$(document).on('focus', 'input, textarea', function () {
    //    if (CHAT_ISMOBILE == 1) {
    //        fnMoveChat('.navbar', 100, 0, 0);
    //    }
    //});




    $("#chatclient #upload-image").html5Uploader({
        name: "upload-image",
        postUrl: "/chat/home/PostImage",
        onServerProgress: function () {
            //$('.loadding').show();
        },
        onSuccess: function (e) {
            var data = $.parseJSON(e.currentTarget.response);
            if (data.message == "upload sucessfully") {
                $inputMessage.val(data.imageUrl);
            }
            if (data.message == "maximum size upload") {
                alert("Dung lượng file upload quá lớn. Tối đa 500KB.");
            }
            if (data.message == "maximum file upload") {
                alert("" + CHAT_DANHXUNG_HOA + " chỉ được phép thêm tối đa 5 hình.");
            }
        },
        onServerError: function (e) {
        }
    });


    if (empty(name) || empty(phone) || empty(gender)) {

       // showchatpage('collect');

    } else {
        //showchatpage('chat');
    }

    getOnlineSupporterAuto();
    resizeChat();
    resizeChatCode();
    if (notifychatmsgdiv) {
        setTimeout(function () {
            socket.emit('chat recheckinvite', { 'usr': username });

        }, 100);

        return;
    }


    setTimeout(function () {
        try {
            prepairInitialData();
            RcmTracking();

        } catch (e) {

        }

    }, 2000);


}
function chatDelCookie(c_name) {
    chatSetCookie(c_name, '', -1, "www." + sitedomain);
    chatSetCookie(c_name, '', -1, "." + sitedomain);


}
function chatSetCookie(c_name, value, exdays, domain) {

    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays == null) ? "" : "; visited=true; " + (domain ? "domain=" + domain : "") + "; path=/; expires=" + exdate.toUTCString() + ";");
    document.cookie = c_name + "=" + c_value;
}
function chatGetCookie2(c_name) {
    var rs = '';
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) {
            rs = unescape(y);
            try {

                rs = (y);
            } catch (err) {

            }

            if (rs == 'undefined') return '';
            return rs;
        }
    }
    return '';
}
function chatGetCookie(c_name) {
    var rs = '';
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) {
            rs = unescape(y);
            try {

                rs = decodeURIComponent(y);
            } catch (err) {

            }

            if (rs == 'undefined') return '';
            return rs;
        }
    }
    return '';
}
function prepairInitialData() {
    LoadUserInfo();
    if (empty(name)) {
        name = 'Khách hàng';
    }
    username = chatGetCookie('chat.username');
    room = username;
    if (empty(username) || username == 'undefined') {
        username = myskid;
        room = myskid;
        chatSetCookie('chat.username', myskid, 365, "." + sitedomain);
    }

}

function setInitialData(data) {

    theOpposite = data.fnm;
    ad_avatar = data.ava;
    if (!data.ava)
        ad_avatar = $avatarCDN + '/nopic' + getExtcdnfilename('.png') + '';
    else
        ad_avatar = $avatarCDN + '/' + data.ava;


    if (empty(name)) name = 'Khách hàng';
    if (empty(theOpposite)) theOpposite = 'Tư Vấn';
    
    loadTitleChatInfoToUI(data);
    LoadUserInfo();
    if (!empty(name)) {
        $('#txtchat_fullname').val(name);
    }
    if (!empty(phone)) {
        $('#txtchat_phone').val(phone);
    }
    if (!empty(gender)) {
        $('#txtchat_gender').val(gender);
    }
    if (!CheckIsMobile()) {
        $('[data-toggle="tooltip"]').tooltip();
    }
}

//save data user chat
var connectcount = 1;
var CMD_CHATCONNECTING = '';
var CMD_CHATCONNECTING_DATA = '';
var CMD_CHATCONNECTING_RUNING = 0;
function fnChatConnecting() {
    var chatusername = chatGetCookie('chat.username');
    if (chatusername) {
        if (chatusername.indexOf('@') >= 0 && CHAT_BOT == 0) {
            return;
        }
    }


    CMD_CHATCONNECTING_RUNING = 1;
    //if (CHAT_ISCHATING == 1 && CHAT_ISCONNECTED == false) {
    //    ChatConnecting('chat register', { cmtid: 0, siteid: CHAT_SITEID, phone: phone, email: email, usr: username, fnm: name, url: window.location.href, 'tid': userType, 'aid': supporter });
    //}
    if (connecting) {
        socket.emit(CMD_CHATCONNECTING, CMD_CHATCONNECTING_DATA);
        enablechat(0);
        shochatwmsg(1, '<span>Hiện các nhân viên đang bận, vui lòng đợi [' + connectcount + ']</span>');

        console.log('connecting....');
    }
    setTimeout(function () {
        // if (connecting) {
        connectcount++;
        console.log('connecting....');
        // }
        fnChatConnecting();
    }, 5000);
}


function ChatConnecting(CMD, data) {
    connectcount = 1;
    connecting = true;
    CMD_CHATCONNECTING = CMD;
    CMD_CHATCONNECTING_DATA = data;
    if (CMD_CHATCONNECTING_RUNING == 0) {
        fnChatConnecting();
    }
}
function checkvalidatechat(data, field) {
    var cllssname = ".vali" + field;
    if (!empty(data)) {
        $(cllssname).show();
        $(cllssname).html('<span style="color:red">' + data + '</span>');
    } else {
        $(cllssname).show();
        $(cllssname).html('');
    }
}
function iniChat() {

    name = $('#txtchat_fullname').val();
    phone = $('#txtchat_phone').val();
    email = $('#txtchat_email').val();
    gender = $('#txtchat_gender').val();
    if (empty(gender)) {
        checkvalidatechat('Vui lòng chọn giới tính', 'fullname');
        return;
    }
    
    runTimmer = 0;


    jQuery.ajax({
        type: "POST",
        url: sitechaturl + "/Home/validator",
        data: "gender=" + gender + "&phone=" + phone + "&name=" + name + "&email=" + email + "&admininvite=" + isadmininvite,
        success: function (data) {
            if (data.c == "1") {
                checkvalidatechat(data.phone);
                SaveCookieUserInfo('', '', '', 0);
                setTimeout(function () {
              
                }, 3000);

            } else if (data.c == "2") {
              
                checkvalidatechat(data.fullname, 'fullname');
                checkvalidatechat(data.phone, 'phone');
                checkvalidatechat(data.email, 'email');


                SaveCookieUserInfo('', '', '', 0);

            } else {
                $("#inimsg").hide();
                $("#inimsg").html('');
                SaveCookieUserInfo(name, email, phone, gender);
                if (iseditinfo == 1) {
                    socket.emit('action changeinfo', { 'rid': username, 'name': name, 'phone': phone, 'gender': gender, 'email': email });
                    // $('.operator-name').html(name);
                } else {

                    if (isInviteChat == 1) { //chat truc tiep ai do
                        connecting = true;
                        addLineMessage();
                        CHAT_ISCHATING = 1;
                        ChatConnecting("chat requestalltoadmin", { 'sourcechat': '', 'catid': g_cateid, 'cmtid': chat_commentid, 'siteid': CHAT_SITEID, 'rid': username, 'usr': username, 'fnm': name, 'phone': phone, 'gender': gender, email: email, 'tid': 'u', 'aid': supporter, url: window.location.href });

                    } else {
                        if (isautochat == 1) {
                            connecting = true;
                            addLineMessage();
                            CHAT_ISCHATING = 1;
                            ChatConnecting(CMD_CHAT_REQUEST, { isbot: CHAT_BOT, 'sourcechat': CHAT_SOURCE, 'catid': g_cateid, 'cmtid': chat_commentid, 'siteid': CHAT_SITEID, 'rid': username, 'usr': username, 'fnm': name, 'phone': phone, 'gender': gender, email: email, 'tid': userType, 'aid': supporter, url: window.location.href });

                        } else {
                            CHAT_ISCHATING = 1;
                            addLineMessage();
                            ChatConnecting(CMD_CHAT_REQUEST, { isbot: CHAT_BOT, 'sourcechat': CHAT_SOURCE, 'catid': g_cateid, 'cmtid': chat_commentid, 'siteid': CHAT_SITEID, 'rid': username, 'usr': username, 'fnm': name, 'phone': phone, 'gender': gender, email: email, 'tid': userType, 'aid': supporter, url: window.location.href });

                        }
                    }
                }
              
                showchatpage('chat');
                resizeChatCode(); 
                addChatInfoMessage();

                iseditinfo = 0;
            }
        }
    });

}

function startChat(greeting) {
    $inputMessage[0].disabled = false;
    $('.chatinput').show();
    $('.chat-start').hide();
    if (greeting == 1) {
        socket.emit('greeting');
    }
}
function refreshTimeAgo() {
    clearInterval(intervalRefresh);
    intervalRefresh = 0;
    intervalRefresh = setInterval('resetTimeAgo()', 5000);
}


function formatDateSent(d) {
    var date = new Date(parseInt(d));
    var seconds = Math.floor((new Date() - d) / 1000);

    var interval = Math.floor(seconds / 31536000);

    if (interval > 1) {
        return date.toString("HH:mm dddd, dd/MM");
    }
    interval = Math.floor(seconds / 2592000);
    if (interval > 1) {
        return date.toString("HH:mm dddd, dd/MM");
    }
    interval = Math.floor(seconds / 86400);
    if (interval > 1) {
        return date.toString("HH:mm dddd, dd/MM");
    }
    interval = Math.floor(seconds / 3600);
    if (interval > 12) {
        return date.toString("HH:mm dddd, dd/MM");
    }
    else if (interval >= 1) {
        return interval + " giờ trước";
    }
    interval = Math.floor(seconds / 60);
    if (interval > 1) {
        return interval + " phút trước";
    }
    interval = Math.floor(seconds);
    if (interval < 5)
        return "Vừa mới gửi";
    else if (interval < 30)
        return interval + " giây trước";
    else if (interval < 60)
        return "Gần 1 phút trước";
    return "1 phút trước";
}
function minizeChatWindow() {
    $chatPage.hide();
    $minimizePage.show();
    isMinimizing = 1;
    chatSetCookie('chat.isminimize', 1, 365, "." + sitedomain);
    $('.msgalert').hide();
    $('.msgalert').html('');
    //$('#chatclient-back').hide();
    $('#chatclient #notifychatmsgmin').hide();
    $('#chatclient #tooltip349481').hide();
    $('#chatclient #notifychatmsg').hide();
   
}
function ChatStopAll() {
    $chatPage.hide();
    $minimizePage.hide();
    $('.msgalert').hide();
    $('.msgalert').html('');
    //$('#chatclient-back').hide();
    chatSetCookie('chat.notifychatmsg', '', 365, "." + sitedomain);
}
function maximizeChatWindow() {
    $chatPage.show();
    $minimizePage.hide();
    isMinimizing = 0;
    chatSetCookie('chat.isminimize', 0, 365, "." + sitedomain);
    unreadCount = 0;
    chatSetCookie('chat.unread', 0, 365, "." + sitedomain);
    $('.msgalert').hide();
    $('.msgalert').html('');

    $('#chatclient #tooltip349481').hide();
    resizeChatCode();
    $inputMessage.focus();
    $messages[0].scrollTop = $messages[0].scrollHeight;
   
}

function closeAndStopChatCMT() {

    showchatpagecmt('close');
    var chatcontent = '';
    var messages = $('#chatclientcmt ul.messages').html();

    var fields = messages.split("~");
    if (fields.length >= 2) {
        chatcontent = fields[fields.length - 1];

    } else {
        chatcontent = messages;
    }
    var nummessage = (chatcontent.match(new RegExp("class=\"me\"", "g")) || []).length;
    if (nummessage <= 0) {
        socket.emit('stop chat', username);
        closechatCMT3();

        return;

    }
    
}

function closeAndStopChat() {
    showchatpage('close');
    //shochatwmsg(0, '');
    //if ($("ul.messages li.me").size() <= 0) {
    //    //minizeChatWindow();
    //    socket.emit('stop chat');
    //    closechat3();
    //    return;
    //}
    var chatcontent = '';
    var messages = $('#chatclient ul.messages').html();

    var fields = messages.split("~");
    if (fields.length >= 2) {
        chatcontent = fields[fields.length - 1];

    } else {
        chatcontent = messages;
    }
    var nummessage = (chatcontent.match(new RegExp("class=\"me\"", "g")) || []).length;
    if (nummessage <= 0) {
        socket.emit('stop chat', username);
        closechat3();
        return;

    }
   
}
function continueStopChat() {
    CHAT_ISCHATING = 0;
    socket.emit('stop chat', username);
    userLeftChat({ username: '' }, CHAT_DANHXUNG_HOA); 
    hidechat();
}
function continueChat() {
    CHAT_ISCHATING = 1; 
    showchatpage('chat');
}
function rateGood() {
    CHAT_ISCHATING = 0;
    socket.emit('stop chat', username);
    $("#chatclient #chatcloseconfirm").html('<div  class="chatthanks" ><h3>CÁM ƠN</h3><p>Cảm ơn ' + CHAT_DANHXUNG + ' đã sử dụng và đánh giá chất lượng tư vấn</p><img src="' + $chatCDN + '/close3' + getExtcdnfilename('.png') + '" onclick="closechat3()" /></div>');

    socket.emit('chat rate', { 'usr': username, 'rate': 1, 'note': $('#chat_moremsg').val(), 'siteid': CHAT_SITEID });
    $('#chatclient .pages').fadeOut(3000, function () {
        closechat3();
    });
}
function rateBad() {
    $("#chatclient #chatcloseconfirm").show();
     $("#chatclient #divchatrating").html('<div class="rateBad" ><p>Xin lỗi đã chưa phục vụ tốt! ' + sitename + ' rất mong nhận được đánh giá của quý khách!</p> <p> <input type="radio" name="chat_rate_check" id="chat_rate_check" value="3"> Lỗi ứng dụng chat, website</p> <p> <input type="radio" name="chat_rate_check" value="4"> Kiến thức của nhân viên tư vấn</p> <p> <input type="radio" name="chat_rate_check" value="6"> Hướng xử lý của nhân viên tư vấn</p> <p> <input type="radio" name="chat_rate_check" value="5"> <textarea  placeholder="Ý kiến khác"  id="chat_rate_note"></textarea></p> <p> <span onclick="rateIngore()" class="rateingrelink" style="width: 113px;   float: left;">Đóng chat ngay</span> <input type="button" name="chat_rate_ht" value="HOÀN TẤT" class="chat_rate_ht" onclick="rateBad2()"> </p></div>');

}
function rateBad2() {
    var manageradiorel = $('input[name = "chat_rate_check"]:checked').val();
    if (!manageradiorel) {
        alert('Mời ' + CHAT_DANHXUNG + ' chọn đánh giá ');
        return;
    }
    CHAT_ISCHATING = 0;
    socket.emit('stop chat', username);
    var note = $('#chatclient #chat_rate_note').val();
    socket.emit('chat rate', { 'usr': username, 'rate': manageradiorel, 'note': note, 'siteid': CHAT_SITEID });
    $("#chatclient #chatcloseconfirm").html('<div class="chatthanks" ><h3>CÁM ƠN</h3><p>Cảm ơn ' + CHAT_DANHXUNG + ' đã sử dụng và đánh giá chất lượng tư vấn</p><img src="' + $chatCDN + '/close3' + getExtcdnfilename('.png') + '" onclick="closechat3()" /> </div>');

    $('#chatclient .pages').fadeOut(3000, function () {
        closechat3();
    });

}
function closechat3() {
    hidechat(); 
}
function rateIngore() {
    $("#chatclient #chatcloseconfirm").html('<div class="chatthanks" ><h3>CÁM ƠN</h3><p>Cảm ơn ' + CHAT_DANHXUNG + ' đã sử dụng và đánh giá chất lượng tư vấn</p><img src="' + $chatCDN + '/close3' + getExtcdnfilename('.png') + '" onclick="closechat3()" /> </div>');
    socket.emit('chat rate', { 'usr': username, 'rate': 0, 'siteid': CHAT_SITEID });
    socket.emit('stop chat', username);
    CHAT_ISCHATING = 0;
    $('#chatclient .pages').fadeOut(3000, function () {
        closechat3();
    });
}
//tat chat CMT

function continueStopChatCMT() {
    CHAT_ISCHATING = 0;
    socket.emit('stop chat', username);
    userLeftChat({ username: '' }, CHAT_DANHXUNG_HOA);
    $("#chatclientcmt #chatcloseconfirm").hide();
    $("#chatclientcmt #chatrating").show(); 
}
function continueChatCMT() {
    CHAT_ISCHATING = 1; 
    showchatpagecmt('chat');

}
function rateGoodCMT() {
    CHAT_ISCHATING = 0;
    socket.emit('stop chat', username);
    $("#chatclientcmt #chatcloseconfirm").html('<div  class="chatthanks" ><h3>CÁM ƠN</h3><p>Cảm ơn ' + CHAT_DANHXUNG + ' đã sử dụng và đánh giá chất lượng tư vấn</p><img src="' + $chatCDN + '/close3' + getExtcdnfilename('.png') + '" onclick="closechatCMT3()" /></div>');

    socket.emit('chat rate', { 'usr': username, 'rate': 1, 'note': $('#chat_moremsg').val(), 'siteid': CHAT_SITEID });
    $('#chatclientcmt .pages').fadeOut(3000, function () {
        closechatCMT3();
    });
}
function rateBadCMT() {
    $("#chatclientcmt #chatcloseconfirm").show();
     $("#chatclientcmt #divchatrating").html('<div class="rateBad" ><p>Xin lỗi đã chưa phục vụ tốt! ' + sitename + ' rất mong nhận được đánh giá của quý khách!</p> <p> <input type="radio" name="chat_rate_check" id="chat_rate_check" value="3"> Lỗi ứng dụng chat, website</p> <p> <input type="radio" name="chat_rate_check" value="4"> Kiến thức của nhân viên tư vấn</p> <p> <input type="radio" name="chat_rate_check" value="6"> Hướng xử lý của nhân viên tư vấn</p> <p> <input type="radio" name="chat_rate_check" value="5"> <textarea  placeholder="Ý kiến khác"  id="chat_rate_note"></textarea></p> <p> <span onclick="rateIngoreCMT()" class="rateingrelink" style="width: 113px;   float: left;">Đóng chat ngay</span> <input type="button" name="chat_rate_ht" value="HOÀN TẤT" class="chat_rate_ht" onclick="rateBadCMT2()"> </p></div>');

}
function rateBadCMT2() {
    var manageradiorel = $('input[name = "chat_rate_check"]:checked').val();
    if (!manageradiorel) {
        alert('Mời ' + CHAT_DANHXUNG + ' chọn đánh giá ');
        return;
    }
    CHAT_ISCHATING = 0;
    socket.emit('stop chat', username);
    var note = $('#chatclientcmt #chat_rate_note').val();
    socket.emit('chat rate', { 'usr': username, 'rate': manageradiorel, 'note': note, 'siteid': CHAT_SITEID });
    $("#chatclientcmt #chatcloseconfirm").html('<div class="chatthanks" ><h3>CÁM ƠN</h3><p>Cảm ơn ' + CHAT_DANHXUNG + ' đã sử dụng và đánh giá chất lượng tư vấn</p><img src="' + $chatCDN + '/close3' + getExtcdnfilename('.png') + '" onclick="closechatCMT3()" /> </div>');

    $('#chatclientcmt .pages').fadeOut(3000, function () {
        closechatCMT3();
    });

}
function closechatCMT3() {
    $('#chatclientcmt .navbar').hide(); 
    CHAT_IN_CMT = 0;
}
function rateIngoreCMT() {
    $("#chatclientcmt #chatcloseconfirm").html('<div class="chatthanks" ><h3>CÁM ƠN</h3><p>Cảm ơn ' + CHAT_DANHXUNG + ' đã sử dụng và đánh giá chất lượng tư vấn</p><img src="' + $chatCDN + '/close3' + getExtcdnfilename('.png') + '" onclick="closechatCMT3()" /> </div>');
    socket.emit('chat rate', { 'usr': username, 'rate': 0, 'siteid': CHAT_SITEID });
    socket.emit('stop chat', username);
    CHAT_ISCHATING = 0;
    $('#chatclient .pages').fadeOut(3000, function () {
        closechatCMT3();
    });
}




function resetTimeAgo() {
    $('#chatclient .msg_sent_at').each(function (idx) {
        var newText = formatDateSent($(this).attr("sent-at"));
        if ($(this).text() != newText) {
            $(this).fadeOut(100, function () {
                $(this).text(newText).fadeIn(800);
            });
        }
    });
}

// Sets the client's username
function setUsername() {
    // If the username is valid
    if (username) {
        $chatPage.show();
        $inputMessage = $inputMessage.focus();
    }
}
function toTimestamp(strDate) {
    var xx= strDate.replace('\/Date(', '');
    xx = xx.replace('https://cdn4.tgdd.vn/v2015/Scripts/desktop/V5/)\/', '');
    debugger
   
    var datum =  (xx);
    
     return datum / 1000;
}
var reISO = /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*))(?:Z|(\+|-)([\d|:]*))?$/;
var reMsAjax = /^\/Date\((d|-|.*)\)[\/|\\]$/;

function dateParser   (  value) {
    if (typeof value === 'string') {
        var a = reISO.exec(value);
        if (a)
            return (value);
        a = reMsAjax.exec(value);
        if (a) {
            var b = a[1].split(/[-+,.]/);
            return  (b[0] ? +b[0] : 0 - +b[1]);
        }
    }
    return value;
};
function getMessageComment() {
    if (chat_commentid > 0) {
       // if (isloadcommnet == 0) {
            $.ajax({
                url: sitechaturl + "/Home/getMessageComment?ParentID=" + chat_commentid, success: function (data) {
                    adminMsgList = {};
                   
                    var lst = JSON.parse(data);
                    var s = '';
                    for (var i in lst) {
                        var msg = (lst[i]);
                        if (msg.CreatedCustomerID) {
                            s = s + GetItemChatMessageComment({
                                username: 'admin',
                                fullname: msg.UserProfile,
                                message: msg.Content,
                                sentAt: dateParser(msg.CreatedDate)
                            });
                        } else {
                            s = s + GetItemChatMessageComment({
                                username: username,
                                fullname: msg.UserProfile,
                                message: msg.Content,
                                sentAt: dateParser(msg.CreatedDate)
                            });
                        }
                    }

                    $('#chatmsg_info_cmt_cmt').html(s);
                    $('#chatmsg_info_cmt').html(s);
                }
            });
            isloadcommnet = 1;
       // }
    }
}

function ratingCommentShow(commentid, type) {
    $('.popratecmt').show();
}
function ratingComment(commentid, type) {

    $.ajax({
        url: sitechaturl + "/Home/ajaxRatingComment?type=" + type + "&commentid=" + commentid, success: function (data) {
            alert('Đã đánh giá thành công');
        }
    });

}
function ratingNoteComment(commentid) {
    var note = $('#chatclient #popratecmttxt').val();
    var name = $('#chatclient #popratecmtname').val();
    var phone = $('#chatclient #popratecmtphone').val();
    if (note.length > 10) {
        $.ajax({
            url: sitechaturl + "/Home/ajaxRatingComment2?type=2&commentid=" + commentid + "&note=" + note + "&name=" + name + "&phone=" + phone, success: function (data) {
                alert('Đã đánh giá thành công');
            }
        });
        $('.popratecmt').hide();
        $('#notifychatnewmsg').hide();
        CloseReply();
    } else {
        alert("Nội dung nhận nhận xét quá ngắn .");
    }

}
function replyBotChat(payload, title) {

    if (connected) {
        var message = '';
        var msgid = "chatmsg_" + new Date().getTime();
        $inputMessage.val('');
        var messagecontentobject = { elements: [{ buttons: [{ title: title, payload: payload }] }] };

        addChatMessage({
            username: username,
            fullname: name,
            message: '',
            sentAt: new Date().getTime(),
            msgid: msgid,
            messagetype: 'template',
            messagecontentobject: messagecontentobject
        });

        socket.emit('new messagebot', { 'postbackobject': { title: title, payload: payload }, 'messagetype': 'payload', 'siteid': CHAT_SITEID, 'msgid': msgid, 'msg': message, 'tid': userType, 'fnm': name, 'curl': location.href }, function (err, success) {

        });

    }
    reseth();
    return false;
}

// Sends a chat message
function sendMessage() {
    var message = $inputMessage.val();
    if (CHAT_IN_CMT) {
        message = $inputMessagecmt.val();
    }
    // Prevent markup from being injected into the message
    message = cleanInput(message).trim();

    if (empty(message)) {
        return;
    }
    // if there is a non-empty message and a socket connection
    if (message && connected) {
        var msgid = "chatmsg_" + new Date().getTime();
        $inputMessage.val('');
        $inputMessagecmt.val('');
        addChatMessage({
            username: username,
            fullname: name,
            message: message,
            sentAt: new Date().getTime(),
            msgid: msgid
        });
        if (message.indexOf('/stop') >= 0) {
            socket.emit('stop chat', username);
        }
        if (CHAT_BOT == 1) {
            socket.emit('new messagebot', { 'siteid': CHAT_SITEID, 'msgid': msgid, 'msg': message, 'tid': userType, 'fnm': name, 'curl': location.href }, function (err, success) {

                if (err) {
                    alert('Gởi không thành công[' + err + ']');
                }
            });
        } else {
            socket.emit('new message', { 'msgid': msgid, 'msg': message, 'tid': userType, 'fnm': name, 'curl': location.href }, function (err, success) {

                if (err) {
                    alert('Gởi không thành công[' + err + ']');
                }
            });
        }



    }
    reseth();
}
// Adds the visual chat message to the message list
var iseditinfo = 0;
function chatuserinfo() {
    showchatpage('collect');
    $('#chatclient .startbutton span').html('LƯU LẠI');
    iseditinfo = 1;
}
function addChatInfoMessageCMT() {
    LoadUserInfo();
    
    var oli2 = $('#chatmsg_info_cmt_cmt');
    if (oli2.length > 0) {
        $('#chatmsg_info_cmt_cmt').html('');
    } else {
        var $messageDiv = $('<li id="chatmsg_info_cmt_cmt" class="chatcmtinfo"/>').html('');
        var $el = $($messageDiv);
        $messagescmt.prepend($el);
        $messagescmt[0].scrollTop = $messagescmt[0].scrollHeight;

    }
}
function addChatInfoMessage() {
    LoadUserInfo();
    var content = '<div><p><span class="cname">' + getGender(gender) + ' ' + name + ' </span><span class="cphone">' + phone + '</span> <a href="javascript:void(0)" onclick="chatuserinfo()">Sửa</a></p><p class="email">' + email + '</p></div>';

    var oli = $('#chatmsg_info');
    if (oli.length > 0) {
        $('#chatmsg_info').html(content);
    } else {
        var $messageDiv = $('<li id="chatmsg_info" class="chatuserinfo">' + content + '</li><li id="chatmsg_info_cmt" class="chatcmtinfo"/>');//.html(content);
        var $el = $($messageDiv);
        $messages.prepend($el);
        $messages[0].scrollTop = $messages[0].scrollHeight;
 
    }
    
}

function expandchat(msgid) {
    var t = $('#exb' + msgid + ' ').html();
    if (t == 'xem thêm') {
        $('#ex' + msgid + '  ').css('overflow', 'auto');
        $('#ex' + msgid + ' ').css('height', 'auto');
        $('#exb' + msgid + ' ').html('thu nhỏ');
    } else {
        $('#ex' + msgid + '  ').css('overflow', 'hidden');
        $('#ex' + msgid + ' ').css('height', '153px');
        $('#exb' + msgid + ' ').html('xem thêm');
    }
}
function addChatMessage(data, options) {
    // Don't fade the message in if there is an 'X was typing'
    var $typingMessages = getTypingMessages(data);
    var $meTypingMessages = getMeTypingMessages(data);
    options = options || {};
    if ($typingMessages.length !== 0) {
        options.fade = false;
        $typingMessages.remove();
    }
    if ($meTypingMessages.length !== 0) {
        options.fade = false;
        $meTypingMessages.remove();
    }

    var typingClass = data.typing ? 'typing' : '';
    var msgid = "chatmsg_" + new Date().getTime();
    if (data.msgid) {
        msgid = data.msgid;
    }
    var content = "";
    if (data.typing) {
        content = '<div><p class="msg_content">' + data.message + '</p></div>';
    } else {

        if (empty(data.messagetype) || data.messagetype == "text") {

            if (CHAT_IN_CMT == false) {
                var tooltipcontent = data.message;
                if (tooltipcontent.length > 150) {
                    tooltipcontent = tooltipcontent.substr(0, 150);
                }
                if (isMinimizing) {
                    $('#tooltip349481').show();
                    $('#tooltip349481 .tooltip-inner').html('<p><span>' + tooltipcontent + '</span></p>');
                    var textContainerHeight = $('#tooltip349481 .tooltip-inner').height();

                    var $ellipsisText = $('#tooltip349481 .tooltip-inner p span');
                    while ($ellipsisText.outerHeight(true) > textContainerHeight) {
                        $ellipsisText.text(function (index, text) {
                            return text.replace(/\W*\s(\S)*$/, '...');
                        });
                    }

                }
            }

            if (data.username == lastmgsuser) {
                content = '<div  >' + (data.fullname == 'MSG' ? '' : ' ' + '<span class=\'msg_sent_at\'    sent-at=\'' + data.sentAt + '\'>' + formatDateSent(data.sentAt) + '</span>') + '<p class="msg_content"   >' + data.message + '</p></div>'

            } else {
                content = '<div  >' + (data.fullname == 'MSG' ? '' : '<span class=\'msg_fnm\'   >' + (!data.fullname || data.fullname == 'undefined' ? 'Khách hàng' : data.fullname) + '</span> ' + '<span  class=\'msg_sent_at\' sent-at=\'' + data.sentAt + '\'>' + formatDateSent(data.sentAt) + '</span>') + '<p class="msg_content"  >' + data.message + '</p></div>'

            }
            lastmgsuser = data.username;


            var $messageDiv = $('<li id="' + msgid + '" class="' + (data.username == username ? "me" : "you") + '" />')
                .data('username', data.username)
                 .html(content)
                .addClass(typingClass);
            if (CHAT_IN_CMT) {
                addMessageElementCMT($messageDiv, options)
            } else {
                addMessageElement($messageDiv, options);
            }


            extractMetaInfo(msgid);
        } else {
            if (data.messagetype == "template") {


                lastmgsuser = data.username;


                var messagecontentobject = data.messagecontentobject;
                var arraybuttons = data.messagecontentobject.elements[0].buttons;
                var title = data.messagecontentobject.elements[0].title;
                var temp = '';
                if (!empty(title)) {
                    temp = ' <span style="font-weight:bold;display: block;font-size: 12px;">' + title + '</span>';
                }

                if (arraybuttons && arraybuttons.length > 0) {

                    for (var i in arraybuttons) {
                        var btn = arraybuttons[i];
                        if (btn.type == 'web_url') {
                            temp = temp + '<a  href="' + btn.url + '" target=_blank   class="botbutton" style="color:blue !important">' + btn.title + '</a>'

                        } else {
                            temp = temp + '<a  href="javascript:void(0)" onclick="replyBotChat(\'' + btn.payload + '\',\'' + btn.title + '\')" class="botbutton">' + btn.title + '</a>'


                        }
                    }
                }
                temp = temp + ' ';
                if (arraybuttons.length > 5) {
                    temp = '<p class="collap" id="ex' + msgid + '"  >' + temp + '</p><span id="exb' + msgid + '"  class="expandbtn" onclick="expandchat(\'' + msgid + '\')">xem thêm</span>';
                }


                if (data.username == lastmgsuser) {
                    content = '<div  ><span class=\'msg_sent_at\'    sent-at=\'' + data.sentAt + '\'>' + formatDateSent(data.sentAt) + '</span><p class="msg_content"   >' + temp + '</p></div>'

                } else {
                    content = '<div  ><span class=\'msg_fnm\'   >' + data.fullname + '</span> <span  class=\'msg_sent_at\' sent-at=\'' + data.sentAt + '\'>' + formatDateSent(data.sentAt) + '</span><p class="msg_content"  >' + temp + '</p></div>'

                }


                var $messageDiv = $('<li id="' + msgid + '" class="' + (data.username == username ? "me" : "you") + '" />')
                    .data('username', data.username)
                     .html(content)
                    .addClass(typingClass);

                if (CHAT_IN_CMT) {
                    addMessageElementCMT($messageDiv, options);
                } else {
                    addMessageElement($messageDiv, options);
                }
                //  extractMetaInfo(msgid);
            }

        }
    }

}

function addChatMessageComment(data, options) {
    var msgid = "chatmsg_" + new Date().getTime();
    if (data.username == lastmgsuser) {
        content = '<div>' + (data.fullname == 'MSG' ? '' : '<span class=\'msg_fnm\' >&nbsp;</span> ' + '<p class=\'msg_sent_at\' sent-at=\'' + data.sentAt + '\'>' + formatDateSent(data.sentAt) + '</p>') + '<p class="msg_content">' + data.message + '</p></div>'

    } else {
        content = '<div>' + (data.fullname == 'MSG' ? '' : '<span class=\'msg_fnm\' >' + (!data.fullname || data.fullname == 'undefined' ? 'Khách hàng' : data.fullname) + '</span> ' + '<p class=\'msg_sent_at\' sent-at=\'' + data.sentAt + '\'>' + formatDateSent(data.sentAt) + '</p>') + '<p class="msg_content">' + data.message + '</p></div>'

    }
    lastmgsuser = data.username;

    var $messageDiv = $('<li id="' + msgid + '" class="' + (data.username == username ? "me" : "you") + '"/>')
        .data('username', data.username)
        .html(content)
        .addClass('');
    addMessageCommentElement($messageDiv, options);

}

function GetItemChatMessageComment(data) {
    var msgid = "chatmsg_" + new Date().getTime();
    if (data.username == lastmgsuser) {
        content = '<div>' + (data.fullname == 'MSG' ? '' : '<span class=\'msg_fnm\' >&nbsp;</span> ' + '<p class=\'msg_sent_at\' sent-at=\'' + data.sentAt + '\'>' + formatDateSent(data.sentAt) + '</p>') + '<p class="msg_content">' + data.message + '</p></div>'

    } else {
        content = '<div>' + (data.fullname == 'MSG' ? '' : '<span class=\'msg_fnm\' >' + (!data.fullname || data.fullname == 'undefined' ? 'Khách hàng' : data.fullname) + '</span> ' + '<p class=\'msg_sent_at\' sent-at=\'' + data.sentAt + '\'>' + formatDateSent(data.sentAt) + '</p>') + '<p class="msg_content">' + data.message + '</p></div>'

    }
    lastmgsuser = data.username;

    return '<li id="' + msgid + '" class="' + (data.username == username ? "me" : "you") + '">' + content + '</li>';


}
function extractMetaInfo(msgid) {
    var msg = $("#" + msgid + ' .msg_content').html();
    $urls = linkify(msg);
    if ($urls.length > 0)
        placeWidgetInContent(msg, $urls, msgid);
}
var $widget_pattern = "<div class='product_widget'><a target=_blank href='{$url}'><div class='product_widget_img'>{$img}</div><div class='product_widget_info'><p class='product_widget_link'>{$title}</p><p class='product_widget_desc'>{$desc}</p><p class='product_widget_sitename'>{$site}</p></div></a></div>";
var $widget_pattern_img = "<div class='product_widget'> <p class='product_widget_imgonly'>{$img}</p> </div>";

function placeWidgetInContent(content, urls, msgid) {
    if (urls.length > 0) {
        $.ajax({
            method: "POST",
            url: sitechaturl + "/home/MetaExtractUrl",
            data: { url: urls[0] }
        }).done(function (msg) {

            if (msg.m == "url") {
           
                content = content.replace(urls[0], "<a  href='" + urls[0] + "' title='" + msg.t + "'>" + urls[0] + "</a>");

                urls.splice(0, 1);
                if (urls.length > 0)
                    return placeWidgetInContent(content, urls, msgid);
                // $("#" + msgid).html(content);




                $("#" + msgid + " .msg_content").html(content);


            }
            if (msg.m == "img") {
                var $product = $widget_pattern_img;
                if (msg.i)
                    $product = $product.replace("{$img}", "<a href='" + msg.i + "' target=_blank><img src='" + msg.i + "' alt='" + msg.t + "' title='" + msg.t + "' /></a>");
                content = content.replace(urls[0], $product);
                urls.splice(0, 1);
                if (urls.length > 0)
                    return placeWidgetInContent(content, urls);
                $("#" + msgid).html(content);
            }

        });
    }
    else {
        $("#" + msgid).html(content);
    }
}

function linkify(text) {
    var urls = new Array();
    var urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@@#\/%?=~_|!:,.;]*[-A-Z0-9+&@@#\/%=~_|])/ig;
    text.replace(urlRegex, function (url) {
        urls.push(url);
    });
    return urls;
}

// Adds the visual chat typing message
function addChatTyping(data) {
    data.typing = true;
    data.fullname = theOpposite;
    data.message = '<em>' + theOpposite + ' đang trả lời...</em>';
    addChatMessage(data);
}
// Removes the visual chat typing message
function removeChatTyping(data) {

    getTypingMessages(data).fadeOut(function () {
        $(this).remove();
    });
    getMeTypingMessages(data).fadeOut(function () {
        $(this).remove();
    });
}
 
function addMessageElement(el, options) {
    var $el = $(el);

    // Setup default options
    if (!options) {
        options = {};
    }
    if (typeof options.fade === 'undefined') {
        options.fade = true;
    }
    if (typeof options.prepend === 'undefined') {
        options.prepend = false;
    }

    // Apply options
    if (options.fade) {
        $el.hide().fadeIn(FADE_TIME);
    }

    if (options.prepend) {
        $messages.prepend($el);
    } else {
        $messages.append($el);
    }
    $messages[0].scrollTop = $messages[0].scrollHeight;

}
function addMessageElementCMT(el, options) {
    var $el = $(el);

    // Setup default options
    if (!options) {
        options = {};
    }
    if (typeof options.fade === 'undefined') {
        options.fade = true;
    }
    if (typeof options.prepend === 'undefined') {
        options.prepend = false;
    }

    // Apply options
    if (options.fade) {
        $el.hide().fadeIn(FADE_TIME);
    }
    //CMT
    if (options.prepend) {
        $messagescmt.prepend($el);
    } else {
        $messagescmt.append($el);
    }
    $messagescmt[0].scrollTop = $messagescmt[0].scrollHeight;

}
function addMessageCommentElement(el, options) {
    var $el = $(el);

    // Setup default options
    if (!options) {
        options = {};
    }
    if (typeof options.fade === 'undefined') {
        options.fade = true;
    }
    if (typeof options.prepend === 'undefined') {
        options.prepend = false;
    }

    // Apply options
    if (options.fade) {
        $el.hide().fadeIn(FADE_TIME);
    }
    if (CHAT_IN_CMT) {
        $messagescmt.prepend($el);
        $messagescmt[0].scrollTop = $messagescmt[0].scrollHeight;
    } else {
        $messages.prepend($el);
        $messages[0].scrollTop = $messages[0].scrollHeight;
    }
}

function striphtml(html) {
    var tmp = document.createElement("DIV");
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || "";
}
function cleanInput(input) {
    var xx = striphtml(input);

    //var xx = $('<div/>').text(input).text();
    return xx;
}
// Updates the typing event
function updateTyping() {
    var message = $inputMessage.val();
    // Prevent markup from being injected into the message
    message = cleanInput(message).trim();

    if (empty(message)) {
        return;
    }
    if (connected) {
        if (!typing) {
            typing = true;
            socket.emit('typing', { tid: userType, rid: username, content: message });
        }
        lastTypingTime = (new Date()).getTime();

        setTimeout(function () {
            var typingTimer = (new Date()).getTime();
            var timeDiff = typingTimer - lastTypingTime;
            if (timeDiff >= TYPING_TIMER_LENGTH && typing) {
                socket.emit('stop typing');
                typing = false;
            }
        }, TYPING_TIMER_LENGTH);
    }
}
// Gets the 'X is typing' messages of a user
function getTypingMessages(data) {
    return $('.you.typing').filter(function (i) {
        return $(this).data('username') === data.username;
    });
}
function getMeTypingMessages(data) {
    return $('.me.typing').filter(function (i) {
        return $(this).data('username') === data.username;
    });
}


function SendCallMeInfo() {

}

function userLeftChat(data, whostop) {
    CHAT_ISCHATING = 0;
    connecting = false;
    shochatwmsg(1, '<span style="color:red">' + whostop + ' đã dừng cuộc hội thoại này</span>');
    enablechat(0);
}

// Accept the chat invitation
function acceptChat() {

}
//Khi nhân notyfy co comment và admin muốn chat với khách , tạo link gọi hàm requestChat(user)
//
function tryRequestChat() {
    $('#chatclient  #chatclient .pages').show();
    $('#chatclient #divmsg').html('');
    requestChat(supporter, chat_commentid);
}

function MinInvitechat() {
    chatSetCookie('chat.ismininvitechat', 1, 365, "." + sitedomain);
    $('#notifychatmsg').hide();
    $('#notifychatmsgmin').show();
    $('#notifychatmsgmin').html(auhtmlinvitechatmini);
    $('#notifychatmsgmin').focus();

}

function addLineMessage() {
    var $messageDiv = $('<li class="linehismessage" style="height:2px;"></li>').html('<span style="font-size:0px;">~</span>');
    addMessageElement($messageDiv);
}
function requestChatInvite(asupporter) {

    chatSetCookie('chat.notifychatmsg', '', 365, "." + sitedomain);
    if (connected == false) {
        alert('Không thể kết nối đến máy chủ chat');
        return;
    }
   
    prepairUI();
    prepairInitialData();
    supporter = asupporter;
    refreshTimeAgo();
    connected = true; 
    $inputMessage.focus();
    runTimmer = 0;
    showchatpage('chat');
  
    addLineMessage();
    CHAT_ISCHATING = 1;
    ChatConnecting('chat request', { cmtid: 0, siteid: CHAT_SITEID, phone: phone,gender : gender, email: email, usr: username, fnm: name, url: window.location.href, 'tid': userType, 'aid': supporter });


}
function requestChat(asupporter, commentid) {

    chatSetCookie('chat.notifychatmsg', '', 365, "." + sitedomain);

    if (connected == false) {
        alert('Không thể kết nối đến máy chủ chat');
        return;
    }
    chat_commentid = commentid;


    
    prepairUI();
    prepairInitialData();
    supporter = asupporter;
    LoadUserInfo();
    if (empty(name) || empty(phone)) {
        
    }


    refreshTimeAgo();
    connected = true;
 
    $('#notifychatmsg').hide();
  //  
    if (empty(name) || empty(phone)) {
        
        showchatpage('collect');
        runTimmer = 1;

        //startTimer(invitetime);
    } else {
        runTimmer = 0;
         
        showchatpage('chat');
    }
    $inputMessage.focus();
    CHAT_ISCHATING = 1;
    if (!empty(name) && !empty(phone)) {
        addLineMessage();
        socket.emit('chat request', { cmtid: chat_commentid, siteid: CHAT_SITEID, phone: phone, email: email,gender : gender, usr: username, fnm: name, url: window.location.href, 'tid': userType, 'aid': supporter });
        addChatInfoMessage();
    } else {
        socket.emit('chat getinfo', { 'username': supporter });
    }



}
function requestChatAuto(sourcechat) {
    CHAT_SOURCE = sourcechat;
    isautochat = 1;
    if (connected == false) {
        alert('Không thể kết nối đến máy chủ chat');
        return;
    }
    chat_commentid = 0;
    //showchat();
    prepairUI();
    prepairInitialData();
    supporter = '';
    LoadUserInfo();

    if (empty(name) || empty(phone)) {
       
    }

    CHAT_ISCHATING = 1;

    refreshTimeAgo();
    connected = true;
    
    if (empty(name) || empty(phone) ||   gender <=0 ) {
        showchatpage('collect'); 
        runTimmer = 1;
    } else {
        runTimmer = 0; 
        showchatpage('chat');
    }

    if (!empty(name) && !empty(phone) &&  (gender>0) ) {
        addLineMessage();
        if (CHAT_BOT == 1) {
            ChatConnecting(CMD_CHAT_REQUEST, { isbot: CHAT_BOT, sourcechat: CHAT_SOURCE, catid: g_cateid, cmtid: chat_commentid, siteid: CHAT_SITEID, phone: phone, email: email, usr: username, fnm: name, gender: gender, url: window.location.href, 'tid': userType, 'aid': 'chatbottgdd' });

        } else {
            ChatConnecting(CMD_CHAT_REQUEST, { isbot: CHAT_BOT, sourcechat: CHAT_SOURCE, catid: g_cateid, cmtid: chat_commentid, siteid: CHAT_SITEID, phone: phone, email: email, usr: username, fnm: name, gender: gender, url: window.location.href, 'tid': userType, 'aid': '' });

        }
        addChatInfoMessage();
    } else {
        SetAutoInfo();
    }
    resizeChatCode();

}
function hidechat() {
    $('#chatclient .navbar').hide();
}
function hideregchat() {
    $('#chatclient #collectdata').hide();
    $('#chatclient .messages').show();
    $('#chatclient .chatinputarea').show();
}
function showregchat() {
    $('#chatclient #collectdata').show();
    $('#chatclient .messages').hide();
    $('#chatclient .chatinputarea').hide();
}
function chatRecheckInfo(name, phone) {
    if (CHAT_SITEID == 8 || CHAT_SITEID == 2) {
        if (empty(name) || empty(phone)) {
            $messages.hide();
            $('#chatclient #collectdata').show();
            $('#chatclient .chatinputarea').hide();
        }
    }

} 
 
function formatCategory(data) {
    if (data[data.length - 1] == ',')
        data = data.substr(0, data.length - 1);
    return data.replace('TV', 'Tivi').replace(/,/g, ', ');
    //if ("undefined" != typeof categoryName)
    //    return categoryName;
    //return "tư vấn";
}
// Socket events
function getOnlineSupporter() {
    socket.emit(CMD_CHAT_ONLINESUPPORTER, { siteid: CHAT_SITEID, catid: g_cateid });
    window.clearTimeout(tmoOnlineSupporter);
    tmoOnlineSupporter = 0;
}
function ChatInviteMe(commentid) {
    var sCookieUserNameChat = chatGetCookie("chat.username");

    if (!empty(sCookieUserNameChat)) {
        var data = {
            security_token: '',
            username: sCookieUserNameChat,
            usernamecustomer: sCookieUserNameChat,
            avatar: "",
            action: "CHAT",
            item: '',
            content: "",
            ChannelID: "",
            commentid: commentid
        };
        socket.emit('chat inviteme', data);
    } else {

        Console.log('cookieuserempti');
    }
}

function getOnlineSupporterAuto() {
    isOutOfWork = CheckOutOfWork();
    if (isOutOfWork == 0) {
        tmoOnlineSupporter = window.setTimeout(function () {
            //if (isInviteChat == 1) {
            getOnlineSupporter();
            // }
        }, CHAT_LOAT_INVITE_TIME);
    }
}
function ChatReply(commentid) {
    $('.chatnotifytoolbox').html('<div class=\"chatnotifytoolbox\"> <textarea  placeholder="Nhập nội dung trả lời"  id="chatreplycomment"></textarea> <a class=\"btnReply\" href=\"javascript:void(0)\" onclick=\"ajaxChatReply(' + commentid + ');\">Trả lời</a> ');
}
function CloseReply() {
    $('#notifychatnewmsg').hide();
}
function ajaxChatReply(commentid) {
    CloseReply();
    var contentcomment = $('#chatreplycomment').val();
    jQuery.ajax({
        type: "POST",
        url: sitechaturl + "/Home/AjaxInsertComment",
        data: "commentid=" + commentid + "&strContent=" + contentcomment,
        success: function (data) {
            if (data.c == "1") {

                alert("Đã gởi thành công");

                // $('.chatnotifytoolbox').html('<div class=\"chatnotifytoolbox\">Đã gởi thành công</div>');

            } else {
                $('.chatnotifytoolbox').html('<div class=\"chatnotifytoolbox\">Có lỗi xảy ra</div>');

            }
        }
    });

}

function SaveCookieUserInfo(name, email, phone, gender) {
    var info = {};
    info.name = name;
    info.phone = phone;
    info.email = email;
    info.gender = gender;
    if (!empty(name) && !empty(gender)) {
        chatSetCookie(cookie_info, JSON.stringify(info), 365, "." + sitedomain);
    }
}
function LoadCokieUserInfo() {

    var str = chatGetCookie(cookie_info);
    if (!empty(str)) {
        var info = JSON.parse(str);
        if (info) {
            if (!empty(info.name)) {
                name = checkempty(info.name);
                phone = checkempty(info.phone);
                email = checkempty(info.email);
                gender = checkempty(info.gender);
            }
        }
    }


}

function LoadUserInfo() {
    var hasInfo = 0;
    var info = {};
    var str = chatGetCookie(cookie_info);
    if (!empty(str)) {
        info = JSON.parse(str);
        if (!empty(info)) {
            if (!empty(info.name)) {
                hasInfo = 1;
                name = checkempty(info.name);
                phone = checkempty(info.phone);
                email = checkempty(info.email);
                gender = checkempty(info.gender);
            }
        }
    }
    var oldname = '';
    var oldphone = '';
    var oldemail = '';
    var oldgender = 0;

    //doc data comment/củ/ban hàng
    if (CHAT_SITEID == 1) {

        oldname = chatGetCookie("tgdd_fullname");
        oldphone = chatGetCookie("tgdd_phone");
        oldemail = chatGetCookie("tgdd_email");
        oldgender = chatGetCookie("tgdd_gender");


    } else if (CHAT_SITEID == 2) {
        var xx = chatGetCookie("DMX_Personal");
        if (!empty(xx)) {
            var vv_newccpf = JSON.parse(xx);
            if (vv_newccpf) {
                var gd = checkempty(vv_newccpf.CustomerSex);

                if (gd == "") oldgender = 0;
                if (gd == "0") oldgender = 1;
                if (gd == "1") oldgender = 2;
                oldname = checkempty(vv_newccpf.CustomerName);
                oldphone = checkempty(vv_newccpf.CustomerPhone);
                oldemail = checkempty(vv_newccpf.CustomerEmail);

            }
        }
        //old+comment
        if (empty(oldname)) {
            oldname = chatGetCookie("dm_fullname");
            oldphone = chatGetCookie("dm_phone");
            oldemail = chatGetCookie("dm_email");
            oldgender = chatGetCookie("dm_gender");
        }



    } else if (CHAT_SITEID == 8) {
        //{%22Id%22:0%2C%22Email%22:null%2C%22NameWithGender%22:%22B%E1%BA%A1n%22%2C%22Name%22:null%2C%22Gender%22:null%2C%22Phone%22:null}
        var xx = chatGetCookie("vv_vcrif");

        if (!empty(xx)) {
            var vv_newccpf = JSON.parse(xx);

            if (vv_newccpf) {

                var gd = checkempty(vv_newccpf.Gender);
                if (empty(gd)) oldgender = 0;
                // nữ 0 nam 1.
                if (gd == "1") oldgender = 1;
                if (gd == "0") oldgender = 2;
                oldname = checkempty(vv_newccpf.Name);
                oldphone = checkempty(vv_newccpf.Phone);
                oldemail = checkempty(vv_newccpf.Email);

            }
        }
        //old+comment
        if (empty(oldname)) {
            oldname = chatGetCookie("dm_fullname");
            oldphone = chatGetCookie("dm_phone");
            oldemail = chatGetCookie("dm_email");
            oldgender = chatGetCookie("dm_gender");
        }
    }
    else {
        //old+comment
        if (empty(oldname)) {
            oldname = chatGetCookie("dm_fullname");
            oldphone = chatGetCookie("dm_phone");
            oldemail = chatGetCookie("dm_email");
            oldgender = chatGetCookie("dm_gender");
        }
    }
    if (oldname != name && !empty(oldname)) {
        name = oldname;
    }
    if (oldphone != phone && !empty(oldphone)) {
        phone = oldphone;
    }
    if (oldemail != email && !empty(oldemail)) {
        email = oldemail;
    }
    if (oldgender != gender && !empty(oldgender)) {
        gender = oldgender;
    }
    info.name = name;
    info.phone = phone;
    info.email = email;
    info.gender = gender;
    if (!empty(name) && !empty(gender)) {
        chatSetCookie(cookie_info, JSON.stringify(info), 365, "." + sitedomain);
    }


}
//Send = 1,
//   TrackRead = 2,
//   TrackClick = 3,
//  TrackView = 4,
//     End = 5,
function RcmTrackingReadCampaign(campaignid, username) {

    var type = 2;
    var url = "https://rtm.thegioididong.com/memtracking?username=" + username + "&campaignid=" + campaignid + "&siteid=" + CHAT_SITEID + "&type=" + type + "&t=" + getRandomInt();

    var imgg = "<img src='" + url + "' style='height:0px;width:0px;'>";
    $('body').append($(imgg));

}
function RcmTrackingClickCampaign(campaignid, username) {

    var type = 3;
    var url = "https://rtm.thegioididong.com/memtracking?username=" + username + "&campaignid=" + campaignid + "&siteid=" + CHAT_SITEID + "&type=" + type + "&t=" + getRandomInt();
    var imgg = "<img src='" + url + "' style='height:0px;width:0px;'>";
    $('body').append($(imgg));

}
function RcmTrackingAjax(currentToken) {
    if (CHAT_SITEID == 2 || CHAT_SITEID == 1) {
        RcmTrackingAjaxPost(currentToken);
        return;
    }
    var campaignid = '';
    var username = chatGetCookie("chat.username");
    LoadCokieUserInfo();
    var type = 4;
    var url = "https://rtm.thegioididong.com/memtracking?cmd=trackcustomer&useragent=" + navigator.userAgent
        + "&token=" + currentToken
        + "&username=" + username
        + "&fullname=" + encodeURIComponent(name)
        + "&gender=" + gender
        + "&address=" + encodeURIComponent(address)
        + "&phone=" + phone
        + "&campaignid=" + campaignid
        + "&siteid=" + CHAT_SITEID
        + "&type=" + type
        + "&url=" + encodeURIComponent(location.href)
        + "&productid=" + g_productid
        + "&manuid=" + g_manuid
        + "&categoryid=" + g_cateid
         + "&categoryname=" + encodeURIComponent(g_categoryname)
         + "&groupcategoryname=" + encodeURIComponent(g_groupcategoryname)
        + "&productprice=" + g_productprice
        + "&t=" + getRandomInt();
    var imgg = "<img src='" + url + "' style='height:0px;width:0px;'>";
    $('body').append($(imgg));

}
function getRandomInt() {
    return Math.floor(Math.random() * 1000);
}
function RcmTrackingAddToCartAjax(addtocart_catname, addtocart_groupcatname) {

    var campaignid = '';
    var username = chatGetCookie("chat.username");
    var type = 4;
    var url = "https://rtm.thegioididong.com/memtracking?cmd=trackaddtocard&siteid=" + CHAT_SITEID + "&type=" + type + "&addtocartcatname=" + addtocart_catname + "&addtocartgroupcatname=" + addtocart_groupcatname + "&t=" + getRandomInt();

    var imgg = "<img src='" + url + "' style='height:0px;width:0px;'>";
    $('body').append($(imgg));

}
function RcmTrackingAjaxPost(currentToken) {

    var username = chatGetCookie("chat.username");
    var formData = new FormData(this);
    formData.append("username", username);


    if (CHAT_SITEID == 1) {
        name = chatGetCookie("tgdd_fullname");
        phone = chatGetCookie("tgdd_phone");
        email = chatGetCookie("tgdd_email");
        gender = chatGetCookie("tgdd_gender");
        //  var gendertemp = chatGetCookie("tgdd_gender");
        // gender = gendertemp == 0 ? 1 : 2;

    } else if (CHAT_SITEID == 2) {

        var xx = chatGetCookie("DMX_Personal");

        if (!empty(xx)) {
            var vv_newccpf = JSON.parse(xx);
            if (vv_newccpf) {
                name = checkempty(vv_newccpf.CustomerName);
                phone = checkempty(vv_newccpf.CustomerPhone);
                email = checkempty(vv_newccpf.CustomerEmail);
                gender = checkempty(vv_newccpf.CustomerSex) == 0 ? 1 : 2;
            }
        }
        if (empty(name)) {
            name = chatGetCookie("dm_fullname");
            phone = chatGetCookie("dm_phone");
            email = chatGetCookie("dm_email");
            gender = chatGetCookie("dm_gender");
        }

    } else if (CHAT_SITEID == 8) {
        var xx = chatGetCookie("vv_newccpf");

        if (!empty(xx)) {
            var vv_newccpf = JSON.parse(xx);
            if (vv_newccpf) {
                name = checkempty(vv_newccpf.CustomerName);
                phone = checkempty(vv_newccpf.CustomerPhone);
                email = checkempty(vv_newccpf.CustomerEmail);
                gender = vv_newccpf.Gender == 0 ? 1 : 2;
            }
        }
        if (empty(name)) {
            name = chatGetCookie("dm_fullname");
            phone = chatGetCookie("dm_phone");
            email = chatGetCookie("dm_email");
            gender = chatGetCookie("dm_gender");
        }
    } else {
        if (empty(name)) {
            name = chatGetCookie("dm_fullname");
            phone = chatGetCookie("dm_phone");
            email = chatGetCookie("dm_email");
            gender = chatGetCookie("dm_gender");
        }
    }

    if (!empty(name)) {
        LoadCokieUserInfo();
    }
    formData.append("fullname", name);
    formData.append("phone", phone);
    formData.append("email", email);
    formData.append("gender", gender);

    formData.append("siteid", CHAT_SITEID);
    formData.append("useragent", navigator.userAgent);
    formData.append("productID", g_productid);
    formData.append("categoryID", g_cateid);
    formData.append("manufactureID", g_manuid);
    formData.append("token", currentToken);
    //them

    formData.append("groupcategoryname", g_groupcategoryname);
    formData.append("categoryname", g_categoryname);
    formData.append("productprice", g_productprice);
    formData.append("url", location.href);




    $.ajax({
        url: sitechaturl + "/Home/trackdata",
        type: 'POST',
        data: formData,
        success: function (data) {

        },
        cache: false,
        contentType: false,
        processData: false
    });
}
function RcmTracking() {
    if (location.href.indexOf("cmpid=") > 0) {
        var url = location.href;
        var campainid = url.match(/cmpid=([\w-]+)/)[1];
        var campainu = url.match(/cmpu=([\w-]+)/)[1];
        RcmTrackingClickCampaign(campainid, campainu);
    }
    if (CHAT_SITEID == 8 || CHAT_SITEID == 2 || CHAT_SITEID == 1) {

        if ("undefined" != typeof messaging) {
            messaging.requestPermission().then(function () {
                console.log('Notification permission granted.');
                messaging.getToken()
                .then(function (currentToken) {
                    if (currentToken) {
                        console.log(currentToken);
                        RcmTrackingAjax(currentToken);
                        return;
                    } else {
                        RcmTrackingAjax('');
                        return;
                    }
                })
                .catch(function (err) {
                    RcmTrackingAjax('');
                    return;
                });
            })
            .catch(function (err) {
                RcmTrackingAjax('');
                return;
            });

        } else {
            RcmTrackingAjax('');
            return;
        }


    }

}


function registerchatwithcomment(strname, intgender, strphone, stremail, commentid) {

    SaveCookieUserInfo(strname, stremail, strphone, intgender);
    name = strname;
    email = stremail;
    phone = strphone;
    gender = intgender;
    hidechat();
    $('#notifychatmsg').hide();
    $('#chattooltipmsg').hide();

    CHAT_IN_CMT = true;
    chatSetCookie('chat.notifychatmsg', '', 365, "." + sitedomain);
    if (connected == false) {
        alert('Không thể kết nối đến máy chủ chat');
        return;
    }
    chat_commentid = commentid;

    isInviteChat = 1;
    isautochat = 1;
    
    //showchatcmt();
    prepairUICMT();
   
    prepairInitialData(); 
    LoadUserInfo();
    if (empty(name) || empty(phone)) {
        
    }


    refreshTimeAgo();
    connected = true;
   
    if (empty(name) || empty(phone)) {
        
    } else {
        runTimmer = 0;
     
    }
    CHAT_ISCHATING = 1;

    if (CHAT_BOT == 1) {
        ChatConnecting(CMD_CHAT_REQUEST, { isbot: CHAT_BOT, sourcechat: CHAT_SOURCE, catid: g_cateid, cmtid: chat_commentid, siteid: CHAT_SITEID, phone: phone, email: email, usr: username, fnm: name, gender: gender, url: window.location.href, 'tid': userType, 'aid': 'chatbottgdd' });

    } else {
        ChatConnecting(CMD_CHAT_REQUEST, { isbot: CHAT_BOT, sourcechat: CHAT_SOURCE, catid: g_cateid, cmtid: chat_commentid, siteid: CHAT_SITEID, phone: phone, email: email, usr: username, fnm: name, gender: gender, url: window.location.href, 'tid': userType, 'aid': '' });

    }
}
function registerchatwithmessage(adminid, msg) {
    chatSetCookie('chat.notifychatmsg', '', 365, "." + sitedomain);
    if (connected == false) {
        alert('Không thể kết nối đến máy chủ chat');
        return;
    }
    // chat_commentid = commentid;
    welcome_msg = msg;
    isInviteChat = 1;
    isautochat = 1;

   
    prepairUI();
    prepairInitialData();
    supporter = adminid;
    LoadUserInfo();
    if (empty(name) || empty(phone)) {
        
    }


    refreshTimeAgo();
    connected = true;
   
    if (empty(name) || empty(phone)) {
        
        showchatpage('collect'); 
    } else {
        runTimmer = 0; 
        showchatpage('chat');
    }
    CHAT_ISCHATING = 1;
    if (!empty(name) && !empty(phone)) {
        addLineMessage();
        socket.emit('chat requestalltoadmin', { cmtid: chat_commentid, siteid: CHAT_SITEID, phone: phone, email: email, usr: username, fnm: name, url: window.location.href, 'tid': userType, 'aid': supporter });
        addChatInfoMessage();
    } else {
        socket.emit('chat getinfo', { 'username': supporter });
    }
}

// Whenever the server emits 'login', log the login message
socket.on('login', function (data) {
    connected = true;
});
//42["chat error",{"code":3,"msg":"quanghaisoft1@yahoo"}]
socket.on('chat error', function (data) {
    if (data) {

     
        enablechat(0);
        var msg = '';
        if (data.code == 1) {
            msg = 'Bạn chưa nhập đủ thông tin chat như Tên, Phone';
        }
        if (data.code == 2) {
            msg = 'Tư vấn viên đang bận, xin thử lại sau';
        }
        if (data.code == 3) {
            msg = 'Tư vấn viên này hiện không oline, xin thử lại sau';
        }
        shochatwmsg(1, '<span>Lỗi:' + msg + '</span>');

    }
});


socket.on('chat ping', function (data) {
    socket.emit('chat pong', { rid: username, siteid: CHAT_SITEID, url: window.location.href });
    //enablechatinput();
});
// Whenever the server emits 'new message', update the chat body
socket.on('new message', function (data) {
    enablechatinput();
    if ($('#chatclient .operator-info .operator-name').length <= 0) {
        socket.emit('chat getinfo', { 'username': supporter });
    }
    connecting = 0;
    addChatMessage(data);

    if (isMinimizing == 1) {
        unreadCount++;
        chatSetCookie('chat.unread', unreadCount, 365, "." + sitedomain);
    }
    $('.msgalert').show();
    $('.msgalert').html('<span>' + unreadCount + '</span>');
    $('#chatclient .chatinputarea .chatmsg').hide();
    if (isadmininvite == 1 && isMinimizing != 1) {
        $('#chatclient .navbar').show();
    }
    shochatwmsg(0, '');

    socket.emit('message ack', { "username": username, "rid": data.roomId, "msgid": data.msgid });

});
socket.on('message ack', function (data) {
    if (data.username != username) {
        //$('#' + data.msgid).css('background', 'red');

    }
});
// Whenever the server emits 'user left', log it in the chat body
socket.on('user left', function (data) {
    removeChatTyping(data);
    $inputMessage[0].disabled = true;
    $inputMessage.attr("disabled", "disabled");
    shochatwmsg(1, '<span style="color:red"></span>');
    setTimeout(function () {
        if (CHAT_ISCHATING == 1 && CHAT_ISCONNECTED == false) {
            ChatConnecting('chat register', { isbot: CHAT_BOT, cmtid: chat_commentid, siteid: CHAT_SITEID, phone: phone, email: email, usr: username, fnm: name, url: window.location.href, 'tid': userType, 'aid': supporter });
        }
    }, 10000);

  //  $('.titlechat .c').show();
});
socket.on('chat disconnect', function (data) {

    enablechat(0);
    shochatwmsg(1, '<span style="color:red"></span>');

    setTimeout(function () {
        if (CHAT_ISCHATING == 1 && CHAT_ISCONNECTED == false) {
            ChatConnecting('chat register', { isbot: CHAT_BOT, cmtid: chat_commentid, siteid: CHAT_SITEID, phone: phone, email: email, usr: username, fnm: name, url: window.location.href, 'tid': userType, 'aid': supporter });
        }
    }, 10000);


});


socket.on('chat getinfo', function (data) {


    LoadUserInfo();
    if (!empty(name)) {
        $('#txtchat_fullname').val(name);
    }
    //chat comment 
    loadTitleChatInfoToUI(data); 
});
socket.on('chat adminoffline', function () {
   enablechat(0);
 
    if (CHAT_ISCHATING == 1) {
        ChatConnecting('chat register', { isbot: CHAT_BOT, catid: g_cateid, cmtid: chat_commentid, siteid: CHAT_SITEID, phone: phone, email: email, usr: username, fnm: name, gender: gender, url: window.location.href, 'tid': userType, 'aid': supporter });
    } else {
        ChatConnecting(CMD_CHAT_REQUEST, { isbot: CHAT_BOT, catid: g_cateid, cmtid: chat_commentid, siteid: CHAT_SITEID, phone: phone, email: email, usr: username, fnm: name, gender: gender, url: window.location.href, 'tid': userType, 'aid': supporter });
    }
     
});
socket.on('chat ok2', function (data) {
    showchatpage('chat');  
    isInviteChat = 0;
});
socket.on('chat ok', function () {
    showchatpage('chat');

    isInviteChat = 0;
});


socket.on('admin left', function (data) {

});
// Whenever the server emits 'typing', show the typing message
socket.on('typing', function (data) {
    addChatTyping(data);
});

// Whenever the server emits 'stop typing', kill the typing message
socket.on('stop typing', function (data) {
    removeChatTyping(data);
});

socket.on('stop chat', function (data) {

    userLeftChat(data, theOpposite);
});
socket.on('selfstop chat', function () {
    $('#chatclient .navbar').hide();
   
});
socket.on('chat userinfo', function (data) {

    if (data.usr != username) {
        setInitialData(data);
    }
});
socket.on('chat setadmininfo', function (data) {

    if (data.usr != username) {
        setInitialData(data);
        if (data.message) {
            addChatMessage({ 'username': data.username, 'message': '<em class=\'stop\'>' + data.message + '</em>', fullname: theOpposite });

        }
    }
});


socket.on('chat changeinfo', function (data) {

    if (data) {
        SaveCookieUserInfo(data.fnm, data.email, data.phone, data.gender);
        name = data.fnm;
        phone = data.phone;
        gender = data.gender;
        chatRecheckInfo(name, phone);
        addChatInfoMessage();
    }
});

socket.on('invite chat', function (data) {
    isadmininvite = 1;
    LoadUserInfo();
    chatRecheckInfo(name, phone);
    if (empty(name)) {
        SaveCookieUserInfo('Khách', '', '', 3);
    }

    setInitialData(data);
    adid = data.tid;
    CHAT_ISCHATING = 1;
    //acceptChat
    prepairInitialData();
    socket.emit('accept chat', { "usr": username, "fnm": name, "rid": room, 'tid': userType, 'aid': adid });
    refreshTimeAgo();
    connected = true; 
    showchatpage('chat');
    requestChatInvite(adid);
});


socket.on('greeting', function (data) {
    addChatMessage(data);
});

socket.on('chat connected', function (data) {
    CHAT_ISCONNECTED = true;
    connected = true;
    myskid = data;
    prepairInitialData();
    if ("undefined" != typeof showMySocketId)
        showMySocketId(data, username);
    if (!chat_enabled)
        return;
    if ("undefined" != typeof isMobile) {
        if (isMobile == true) g_ostype = 2;
    }
    LoadUserInfo();
    var chatusername = chatGetCookie('chat.username');
    if (chatusername) {
        if (chatusername.indexOf('@') >= 0 && CHAT_BOT == 0) {
            return;
        }
    }


    CHAT_ISCHATING = 1;
    console.log('chat connected');
    socket.emit('chat register', { isbot: CHAT_BOT, ostype: g_ostype, cmtid: chat_commentid, isauto: 1, siteid: CHAT_SITEID, phone: phone, email: email, usr: username, fnm: name, url: window.location.href });

});
socket.on('admin joined', function (data) {
    CHAT_ISCONNECTED = true;
    connecting = false;
    enablechat(1);
    shochatwmsg(0, '');
    connected = true;
    adid = data.tid;
    CHAT_ISCHATING = 1;
    supporter = data.usr;
    if (CHAT_IN_CMT == 1) {
        showchatpagecmt('chat');
    } else {
        showchatpage('chat');
    }
    setInitialData(data);
    if (!empty(welcome_msg)) {
        var msgid = "chatmsg_" + new Date().getTime();
        addChatMessage({
            username: username,
            fullname: name,
            message: welcome_msg,
            sentAt: new Date().getTime(),
            msgid: msgid
        });
        socket.emit('new message', { 'msgid': msgid, 'msg': welcome_msg, 'tid': userType, 'fnm': name, 'curl': location.href });
        welcome_msg = '';
    }
    if (!empty(data.message)) {
        var msgid = "chatmsg_" + new Date().getTime();
        addChatMessage({
            username: data.usr,
            fullname: data.fnm,
            message: data.message,
            sentAt: new Date().getTime(),
            msgid: msgid
        });
    }

});
socket.on('continue chat', function (data) {
    CHAT_ISCONNECTED = true;
    connecting = false;
    if (CHAT_IN_CMT == 1) {
        showchatpagecmt('chat');
    } else {
        showchatpage('chat');
    }
    shochatwmsg(0, '');
   
    enablechat(1);
    LoadUserInfo();
    chatRecheckInfo(name, phone);
    runTimmer = 0;
    CHAT_ISCHATING = 1;
    if (empty(name)) {
        ChatStopAll();
        return;
    }

    supporter = data.opposite.usr;
    connected = true;
    setUsername();
    theOpposite = data.opposite.fnm; 
    $messages.empty();
    chat_commentid = data.user.cmtid;
    getMessageComment();
    for (var m in data.msgs) {
        addChatMessage(JSON.parse(data.msgs[m]));
    }
    addChatInfoMessage();

    refreshTimeAgo();
    // socket.emit('chat reconnect', data.user.usr);
    socket.emit('chat reconnect', data.user);
    socket.emit('chat getinfo', { 'username': supporter });
    if (isMinimizing == 1) {
        minizeChatWindow();
        if (unreadCount > 0) {
            $('.msgalert').show();
            $('.msgalert').html('<span>' + unreadCount + '</span>');
        }
    }
    resizeChatCode();
});
socket.on('connect_error', function () {
    console.log('Failed to connect to server'); 
    CHAT_ISCONNECTED = false;
    enablechat(0);
});
socket.on('disconnect', function () {
    CHAT_ISCONNECTED = false; 
    enablechat(0);
});
socket.on('chat reconnect', function () {
    shochatwmsg(0, '');
    CHAT_ISCHATING = 1;
    CHAT_ISCONNECTED = true;
    enablechat(1);
    console.log('chat reconnect');
});
socket.on('chat status', function (data) {
    socket.emit('chat statusreply', data);
});
socket.on('chat recheckinvite', function (data) {

    if (data.code == 1) {
        var user = data.user;
        if (user && user.InviteObject) {
            var ad_avatar = '';

            if (!user.InviteObject.avatar)
                ad_avatar = $avatarCDN + '/nopic.png';
            else
                ad_avatar = $avatarCDN + '/' + user.InviteObject.avatar;
            var htmlinvitechat = "<div class=\"notifyChat\"><img src=\"{avatar}\"><div class=\"ctNotify\"><p>" + CHAT_DANHXUNG_HOA + " có muốn chat với Tư Vấn Bán Hàng {name} để được hỗ trợ ngay không?</p><a class=\"btnRqChat\" href=\"javascript:void(0)\" onclick=\"requestChat('{username}', '{commentid}');\">CHAT NGAY</a><a class=\"seeCmt\" href=\"javascript:void(0)\" onclick=\"HideInviteChat();\">Không, cảm ơn</a></div></div>";
            htmlinvitechat = htmlinvitechat.replace('{username}', user.InviteObject.ctvusername);
            htmlinvitechat = htmlinvitechat.replace('{name}', user.InviteObject.ctvname);
            htmlinvitechat = htmlinvitechat.replace('{avatar}', ad_avatar);
            htmlinvitechat = htmlinvitechat.replace('{commentid}', user.InviteObject.commentid);
            $('#notifychatmsg').show();
            $('#notifychatmsg').html(htmlinvitechat);
            $('#notifychatmsg').focus();
            var seconds = Math.floor((new Date() - user.InviteAt) / 1000);
            runTimmer = 1;
            startTimer(90 - seconds);
            invitetime = seconds;
        }

    } else {
        invitetime = 0;

    }

});


socket.on('chat reset', function () {
    chatSetCookie('chat.username', '', -1, "www." + sitedomain);
    chatSetCookie('chat.username', '', -1, "." + sitedomain);
    chatSetCookie('chat.username', '', -1, "rtm." + sitedomain)
});

socket.on('chat deny', function () {
    CHAT_ISCONNECTED = false;
    ChatConnecting('chat register', { isbot: CHAT_BOT, ostype: g_ostype, cmtid: chat_commentid, isauto: 1, siteid: CHAT_SITEID, phone: phone, email: email, usr: username, fnm: name, url: window.location.href });

});
socket.on('chat waiting', function (data) {
    if (checkvisible('#chatclient .messages')) {
        shochatwmsg(1, '<span>' + getGender(gender) + ' ' + name + ' vui lòng đợi trong giây lát...</span>');
    }
});
socket.on('connect', function () {
    if ("undefined" != typeof cmtroom) {
        socket.emit('join', cmtroom);
        console.log('join=' + cmtroom);
    }
});

socket.on("rdata", function (data) {

    var obj = (data);

    var sCookieUserNameChat = chatGetCookie("chat.username");
    var ad_avatar = '';

    if (!obj.avatar)
        ad_avatar = $avatarCDN + '/nopic' + getExtcdnfilename('.png') + '';
    else
        ad_avatar = $avatarCDN + '/' + obj.avatar;
    if (obj.action == "CHAT") {
        var htmlinvitechat = "<div class=\"cmtnotifyChat\"><img src=\"{avatar}\"><div class=\"ctNotify\"><p>" + CHAT_DANHXUNG_HOA + " có muốn chat ngay với nhân viên tư vấn bán hàng {name}?</p><a class=\"btnRqChat\" href=\"javascript:void(0)\" onclick=\"requestChat('{username}', '{commentid}');\">CHAT NGAY</a><a class=\"seeCmt\" href=\"javascript:void(0)\" onclick=\"HideInviteChat();\">Không, cảm ơn</a></div></div>";
        htmlinvitechat = htmlinvitechat.replace('{username}', obj.ctvusername);
        htmlinvitechat = htmlinvitechat.replace('{name}', obj.ctvname);
        htmlinvitechat = htmlinvitechat.replace('{avatar}', ad_avatar);
        htmlinvitechat = htmlinvitechat.replace('{commentid}', obj.commentid);
        $('#notifychatmsg').show();
        $('#notifychatmsg').html(htmlinvitechat);
        $('#notifychatmsg').focus();
        chatSetCookie('chat.notifychatmsg', '1', 1, "." + sitedomain);
        if (obj.ctvname) {
            changeTitlePage("QTV " + obj.ctvname + " vừa trả lời bạn:" + obj.content);
        }
        runTimmer = 1;
        startTimer(90);
    } else if (obj.action == "NEWMESSAGE") {

        if (CHAT_SITEID == 8) {
            try {
                vuivui_showcomment(obj);

            } catch (e) {

            }
            return;
        }
        var htmlinvitechat = "<div class=\"notifyNewChat\"><span onclick=\"CloseReply()\" class=\"close\">X</span><div class=\"ctNotify\"><p><b>{name}</b> vừa trả lời cho <b>{namecustomer}:</b></p><p>\"{content}\"</p>{cmtlink} <div class=\"chatnotifytoolbox\"><div class=\"popratecmt\"><h4>Mời " + CHAT_DANHXUNG + " góp ý để QTV {name} phục vụ tốt hơn : </h4><input id=\"popratecmttxt\"  placeholder=\"Nội dung góp ý\" type=\"text\"><input id=\"popratecmtname\" placeholder=\"Họ tên\" type=\"text\"><input id=\"popratecmtphone\" placeholder=\"Số điện thoại\" type=\"text\"><input type=\"button\" value=\"GỬI\" onclick=\"ratingNoteComment('{commentid}')\"></div> <a class=\"btnReply\" href=\"javascript:void(0)\" onclick=\"ChatReply('{commentid}');\">Trả lời</a><a class=\"cmtrating\" href=\"javascript:void(0)\" onclick=\"ratingComment('{commentid}',1);\">Hài lòng</a> <a class=\"cmtrating\" href=\"javascript:void(0)\" onclick=\"ratingCommentShow('{commentid}',2);\">Không hài lòng</a></div></div> </div>";
        //  var htmlinvitechat = "<div class=\"notifyNewChat\"><span onclick=\"CloseReply()\" class=\"close\">X</span><div class=\"ctNotify\"><p><b>{name}</b> vừa trả lời cho <b>{namecustomer}:</b></p><p>\"{content}\"</p>{cmtlink} <div class=\"chatnotifytoolbox\"><div class=\"popratecmt\"><h4>Mời " + CHAT_DANHXUNG + " góp ý để QTV {name} phục vụ tốt hơn : </h4><input id=\"popratecmttxt\"  placeholder=\"Nội dung góp ý\" type=\"text\"><input id=\"popratecmtname\" placeholder=\"Họ tên\" type=\"text\"><input id=\"popratecmtphone\" placeholder=\"Số điện thoại\" type=\"text\"><input type=\"button\" value=\"GỬI\" onclick=\"ratingNoteComment('{commentid}')\"></div> <a class=\"btnReply\" href=\"javascript:void(0)\" onclick=\"ChatReply('{commentid}');\">Trả lời</a><a class=\"cmtrating\" href=\"javascript:void(0)\" onclick=\"ratingComment('{commentid}',1);\">Hài lòng</a> <a class=\"cmtrating\" href=\"javascript:void(0)\" onclick=\"ratingCommentShow('{commentid}',2);\">Không hài lòng</a></div></div><div class=\"chatreplymsg\"><table><tr><td valign='top'><img scr=\"" + ad_avatar + "\" class='chatreplymsg-img'></td><td  valign='top'><span style='font-weight: bold; color: blue;'>Chat nhanh với " + obj.ctvname + "</span><textarea id=\"chatreplymsg\" class='chatreplymsg-textarea'> </textarea><input class='btnReply' type=\"button\" style='float:left;height:28px' value=\"GỬI\" onclick=\"registerchatwithmessage('" + obj.ctvusername + "')\"></td></tr></table></div>";

        htmlinvitechat = htmlinvitechat.replace('{username}', obj.ctvusername);
        htmlinvitechat = htmlinvitechat.replace('{name}', obj.ctvname);
        htmlinvitechat = htmlinvitechat.replace('{name}', obj.ctvname);
        htmlinvitechat = htmlinvitechat.replace('{avatar}', ad_avatar);
        htmlinvitechat = htmlinvitechat.replace('{commentid}', obj.commentid);
        htmlinvitechat = htmlinvitechat.replace('{commentid}', obj.commentid);
        htmlinvitechat = htmlinvitechat.replace('{commentid}', obj.commentid);

        if (!empty(obj.cmtlink)) {
            htmlinvitechat = htmlinvitechat.replace('{cmtlink}', '<p class="chatcmtlink">Xem bình luận tại link: <a target=_blank href="' + obj.cmtlink + '">' + obj.cmtlink + '</a></p>');
        } else {
            htmlinvitechat = htmlinvitechat.replace('{cmtlink}', '');
        }


        htmlinvitechat = htmlinvitechat.replace('{namecustomer}', obj.namecustomer);
        htmlinvitechat = htmlinvitechat.replace('{content}', obj.content);
        $('#notifychatnewmsg').show();
        $('#notifychatnewmsg').html(htmlinvitechat);
        $('#notifychatnewmsg').focus();
        $('#notifychatnewmsg').center();
        // $('#chatclient-backfull').show();
        $('.chatnotifytoolbox .btnReply').show();
        if (obj.ctvname) {
            changeTitlePage("QTV " + obj.ctvname + " vừa trả lời bạn:" + obj.content);
        }



    } else {

        var str = "<b>" + obj.username + "</b> " + obj.action + " " + obj.item + ':<i>' + obj.content + '</i>';
        cmtnew = cmtnew + 1;
        $('#notifycmtmsg').removeClass("hide");
        $('#notifycmtmsg').html("Có bình luận mới thêm vào. <a href='javascript:' onclick='getCmtNotify();'>Click để xem </a>");
        $('#notifycmtmsg').removeClass('fixedChat');


    }

});

socket.on('chat admininvite', function (data) {
    isadmininvite = 1;

});

socket.on('chat onlinesupporter', function (datalist) {
    if (CHAT_BOT == 1) {
        ViewInviteChat(datalist);
    } else {
        var currentHour = new Date().getHours();
        var numCanChat = 0;
        var isbusylist = datalist.isbusylist;
        var list = datalist.list;
        if (!datalist.list) {
            return;
        }
        if (list && list.length > 0) {
            var list1 = [];
            var list2 = [];
            for (var i in list) {
                var user = list[i];
                list2.push(user);
                numCanChat++;
            }
        }
        console.log("numCanChat=" + numCanChat);

        if (numCanChat > 0) {
            ViewInviteChat(list2);

        } else {
            HideInviteChat();
        }
    }
    getOnlineSupporterAuto('');

});

socket.on('chat onlinesupporter2018', function (datalist) {
    if (CHAT_BOT == 1) {
        ViewInviteChat(datalist);

    } else {
        var currentHour = new Date().getHours();
        var numCanChat = 0;
        var isbusylist = datalist.isbusylist;
        var list = datalist.list;
        if (!datalist.list) {
            return;
        }
        var list1 = [];
        var list2 = [];
        if (list && list.length > 0) {

            for (var i in list) {
                var user = list[i];
                if (user.isbusy == false) {
                    list2.push(user);
                    numCanChat++;
                }
            }
        }

        console.log("numCanChat=" + numCanChat);
        if (CHAT_SITEID == 8) {
            if (typeof chathead == 'function') {
                try {

                    chathead(list2);
                } catch (e) {

                }
            } else {
                if (numCanChat > 0) {
                    ViewInviteChat(list2);

                } else {
                    HideInviteChat();
                }
            }


        } else {
            if (numCanChat > 0) {
                ViewInviteChat(list2);

            } else {
                HideInviteChat();
            }
        }
    }
    getOnlineSupporterAuto('');

});
prepairUI(); 
prepairUICMT();