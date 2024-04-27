const path = require("path");
const miniCss = require("mini-css-extract-plugin");
const TerserPlugin = require("terser-webpack-plugin");

// требуется указать полный путь до темы.
// const relativePath = '/wp-content/themes/new-theme/dist/';

module.exports = (env) => {
  const isProduction = env.mode === "production";
  //
  const mode = isProduction ? "production" : "development";
  const needClean = true;
  const watch = env.watch === "watch";
  const fileName = isProduction ? "[name].[chunkhash]" : "[name]";
  const chunkName = isProduction ? "[name].[chunkhash].chunk" : "[name].chunk";

  return {
    mode: mode,
    // stats: 'detailed',
    devtool: isProduction ? false : "inline-source-map",

    entry: {
      index: "./src/js/index.js",
    },

    output: {
      path: path.resolve(__dirname, "./dist"),
      filename: "js/" + fileName + ".js",
      publicPath: "auto", // Если есть проблемы с определением пути чанков, заменяется на relativePath, описанный в 7 строке.
      chunkFilename: `js/scripts/${chunkName}.js`,
      asyncChunks: true, // создавать отдельные чанки для import ('./a.js'); загруженных модулей
      clean: needClean,
      assetModuleFilename: "assets/[name][ext]?v=[hash]",
    },
    optimization: {
      moduleIds: "named",
      minimizer: [
        new TerserPlugin({
          extractComments: false,
        }),
      ],
      runtimeChunk: {
        name: "runtime",
      },
      splitChunks: {
        hidePathInfo: true,
        minSize: 0,
        automaticNameDelimiter: "-",
        maxInitialRequests: Infinity,
        cacheGroups: {
          vendor: {
            test: /[\\/]node_modules[\\/](.*)[\\/]/,
            name: "vendor",
          },
        },
      },
    },

    plugins: [
      new miniCss({
        filename: `css/${fileName}.css`,
        chunkFilename: `css/${chunkName}.css`,
      }),
    ],

    module: {
      rules: [
        {
          test: /\.scss$/,
          use: [
            {
              loader: miniCss.loader,
            },
            {
              loader: "css-loader",
              options: {
                url: true,
              },
            },
            {
              loader: "postcss-loader",
              options: {
                postcssOptions: {
                  plugins: [
                    [
                      "postcss-preset-env",
                      {
                        // Options
                      },
                    ],
                  ],
                },
              },
            },
            {
              loader: "sass-loader",
            },
          ],
        },
        {
          test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
          type: "asset/resource",
          generator: {
            filename: "fonts/[name][ext][query]",
          },
        },
      ],
    },

    watch: !mode || watch,

    watchOptions: {
      ignored: ["node_modules/**"],
    },
  };
};
