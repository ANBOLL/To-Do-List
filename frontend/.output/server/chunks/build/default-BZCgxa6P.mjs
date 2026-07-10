import { mergeProps, unref, useSSRContext } from 'vue';
import { ssrRenderAttrs, ssrInterpolate, ssrRenderSlot } from 'vue/server-renderer';
import { u as useAuth } from './useAuth-D94ixnlA.mjs';
import { _ as _export_sfc } from './server.mjs';
import '../nitro/nitro.mjs';
import 'node:http';
import 'node:https';
import 'node:events';
import 'node:buffer';
import 'node:fs';
import 'node:path';
import 'node:crypto';
import 'node:url';
import '../routes/renderer.mjs';
import 'vue-bundle-renderer/runtime';
import 'unhead/server';
import 'devalue';
import 'unhead/utils';
import 'vue-router';

const _sfc_main = {
  __name: "default",
  __ssrInlineRender: true,
  setup(__props) {
    const { user } = useAuth();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "layout" }, _attrs))} data-v-1b01ddf1>`);
      if (unref(user)) {
        _push(`<header class="header" data-v-1b01ddf1><div class="container header-inner" data-v-1b01ddf1><h1 class="logo" data-v-1b01ddf1>Todo App</h1><nav class="nav" data-v-1b01ddf1><span class="user-info" data-v-1b01ddf1>${ssrInterpolate(unref(user).name)} (${ssrInterpolate(unref(user).role)})</span>`);
        if (unref(user).isAdmin) {
          _push(`<a href="http://localhost:8000/admin" target="_blank" class="btn btn-secondary" data-v-1b01ddf1>Admin Panel</a>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<button class="btn btn-danger" data-v-1b01ddf1>Logout</button></nav></div></header>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<main class="main" data-v-1b01ddf1><div class="container" data-v-1b01ddf1>`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</div></main></div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("layouts/default.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const _default = /* @__PURE__ */ _export_sfc(_sfc_main, [["__scopeId", "data-v-1b01ddf1"]]);

export { _default as default };
//# sourceMappingURL=default-BZCgxa6P.mjs.map
