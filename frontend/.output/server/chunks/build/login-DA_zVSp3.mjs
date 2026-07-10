import { ref, mergeProps, unref, useSSRContext } from 'vue';
import { ssrRenderAttrs, ssrRenderAttr, ssrRenderClass, ssrInterpolate, ssrIncludeBooleanAttr, ssrRenderStyle } from 'vue/server-renderer';
import { u as useAuth } from './useAuth-D94ixnlA.mjs';
import { _ as _export_sfc, u as useRouter } from './server.mjs';
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
  __name: "login",
  __ssrInlineRender: true,
  setup(__props) {
    const email = ref("");
    const password = ref("");
    const { loading, error: authError } = useAuth();
    useRouter();
    const fieldError = ref(false);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "login-page" }, _attrs))} data-v-5a91a7aa><div class="login-card card" data-v-5a91a7aa><h1 class="login-title" data-v-5a91a7aa>Todo App</h1><p class="login-subtitle" data-v-5a91a7aa>Sign in to manage your tasks</p><form class="login-form" data-v-5a91a7aa><div class="field" data-v-5a91a7aa><label for="email" data-v-5a91a7aa>Email</label><input id="email"${ssrRenderAttr("value", unref(email))} type="email" class="${ssrRenderClass([{ "input-error": unref(fieldError) }, "input"])}" placeholder="you@example.com" required data-v-5a91a7aa></div><div class="field" data-v-5a91a7aa><label for="password" data-v-5a91a7aa>Password</label><input id="password"${ssrRenderAttr("value", unref(password))} type="password" class="${ssrRenderClass([{ "input-error": unref(fieldError) }, "input"])}" placeholder="••••••••" required data-v-5a91a7aa></div>`);
      if (unref(authError)) {
        _push(`<div class="error-state" data-v-5a91a7aa>${ssrInterpolate(unref(authError))}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<button type="submit" class="btn btn-primary login-btn"${ssrIncludeBooleanAttr(unref(loading)) ? " disabled" : ""} data-v-5a91a7aa>`);
      if (unref(loading)) {
        _push(`<span class="spinner" style="${ssrRenderStyle({ "width": "16px", "height": "16px", "border-width": "2px", "margin": "0" })}" data-v-5a91a7aa></span>`);
      } else {
        _push(`<!---->`);
      }
      _push(` ${ssrInterpolate(unref(loading) ? "Signing in..." : "Sign In")}</button></form></div></div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("pages/login.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const login = /* @__PURE__ */ _export_sfc(_sfc_main, [["__scopeId", "data-v-5a91a7aa"]]);

export { login as default };
//# sourceMappingURL=login-DA_zVSp3.mjs.map
