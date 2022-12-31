export const removeQueryParams = (path: string): string => {
  return (path === "/" ? "" : path).split("?")[0];
};
