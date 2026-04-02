import React from 'react'

declare global {
  namespace JSX {
    interface IntrinsicElements extends React.JSX.IntrinsicElements {
      [elemName: string]: any;
    }
  }
}

declare module 'react-dom/client' {
  export interface Root {
    render(children: React.ReactNode): void;
    unmount(): void;
  }
  export function createRoot(container: Element | DocumentFragment): Root;
}

declare module 'react' {
    export = React;
}

declare module 'react/jsx-runtime' {
    import { JSX } from 'react';
    export default JSX;
}
