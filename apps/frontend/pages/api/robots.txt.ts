import { NextApiRequest, NextApiResponse } from "next";
import { configuration } from "utils/configuration";

const generateSitemap = async (
  _req: NextApiRequest,
  res: NextApiResponse
): Promise<NextApiResponse | void> => {
  res.setHeader("content-type", "text/plain");
  return res.send(`
User-agent: *
Allow: /
Host: ${configuration.appUrl}
Sitemap: ${configuration.appUrl}/sitemap.xml
Disallow: /invitations
Disallow: /me/
`);
};

export default generateSitemap;
