"use client";

import { signIn } from "next-auth/react";

export const Login = () => (
  <button onClick={() => signIn("keycloak")}>Sign in</button>
);
