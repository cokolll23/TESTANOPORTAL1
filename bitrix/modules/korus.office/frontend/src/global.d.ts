declare namespace BX {
  interface LeftMenu {
    isCollapsedMode:   boolean;
    menuBody:          HTMLElement;
    menuContainer:     HTMLElement;
    menuHeaderBurger:  HTMLElement;
    handleBurgerClick: (state?: boolean) => void;
  }

  interface Intranet {
    LeftMenu: LeftMenu;
  }

  interface Bitrix24 {
    LeftMenu: LeftMenu;
  }

  interface Messenger {
    v2: {
      Lib: {
        Opener: {
          openChat: (dialogId: string, messageId?: number) => Promise<unknown>;
          startVideoCall: (dialogId: string, withVideo?: boolean) => Promise<unknown>;
        };
      };
    };
  }

  interface Runtime {
    loadExtension: (name: string | string[]) => Promise<unknown>;
    html: (node: HTMLElement, html: any, params: any) => Promise<unknown>;
  }

  interface Date {
    format: (format: string, timestamp?: number | Date, now?: number | Date, utc?: boolean) => string;
  }

  interface UI {
    EntitySelector: {
      Dialog: any;
    };
  }

  interface Kt {
    Selector: any;
  }

  interface SidePanel {
    Instance: {
      open: (path: string, options: Record<string, unknown>) => void;
    };
  }

  const Intranet: Intranet;
  const Bitrix24: Bitrix24;
  const Messenger: Messenger;
  const Kt: Kt;
  const Runtime: Runtime
  const UI: UI;
  const date: Date;
  const SidePanel: SidePanel;

  const ready: (cb: (...args: any[]) => any) => void;
  const namespace: (name: string) => void
  const bitrix_sessid: () => string
  const message: (key: string) => string

  const height: (el: null | HTMLElement) => number

  const load: ((cssAssets: string[], callback: (...args: unknown[]) => any, doc?: Document) => void)
  const loadScript: ((jsAssets: string | string[], callback: (...args: unknown[]) => any, doc?: Document) => void)
  const html: ((elem: HTMLElement, html: string, config: Record<string, unknown>) => Promise<void>)
  const create: (tag: string, props: Record<string, unknown>) => HTMLElement
}

declare namespace BXIM {
  const openMessenger: (id: number | string) => any;
  const callTo: (id: number | string) => any;
}
