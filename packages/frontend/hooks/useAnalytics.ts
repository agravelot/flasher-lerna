import ReactGA, { EventArgs, FieldsObject, TrackerNames } from "react-ga";
import { configuration } from "~/utils/configuration";

interface AnalyticsHook {
  initialize: () => void;
  pageView: (path?: string) => void;
  event: (args: EventArgs, trackerNames?: TrackerNames) => void;
  exception: (fieldsObject: FieldsObject, trackerNames?: TrackerNames) => void;
}

export const useAnalytics = (): AnalyticsHook => {
  return {
    initialize: () => {
      ReactGA.initialize(configuration.googleAnalytics.ua);
    },
    pageView: (path?: string) => {
      if (path) {
        return ReactGA.pageview(path);
      }
      return ReactGA.pageview(
        window.location.pathname + window.location.search
      );
    },
    event: (args: EventArgs, trackerNames?: TrackerNames) => {
      ReactGA.event(args, trackerNames);
    },
    exception: (
      fieldsObject: FieldsObject,
      trackerNames?: TrackerNames
    ): void => {
      ReactGA.exception(fieldsObject, trackerNames);
    },
  };
};
