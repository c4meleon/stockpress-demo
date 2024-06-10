import React from "react";
import "./App.css";
import { QueryClientProvider, QueryClient } from "@tanstack/react-query";
import { Link, NavLink, Outlet, Route, Routes } from "react-router-dom";

import Images from "./app/images/page";
import Upload from "./app/upload/page";

const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      refetchOnWindowFocus: false,
      retry: false,
    },
  },
});

function App() {
  return (
    <div className="App">
      <QueryClientProvider client={queryClient}>
        <Routes>
          <Route path="/" element={<Layout />}>
            <Route index element={<Images />} />
            <Route path="upload" element={<Upload />} />
            <Route path="*" element={<NoMatch />} />
          </Route>
        </Routes>
      </QueryClientProvider>
    </div>
  );
}

function Layout() {
  return (
    <div className="">
      <nav className="p-3 sticky top-0 z-50 bg-white border-b-2">
        <ul className="flex flex-column gap-2">
          <li className="p-2">
            <NavLink
              to="/"
              className="text-sm font-semibold leading-6 text-gray-900"
            >
              {({ isActive }) => (
                <span className={isActive ? "text-yellow-500" : ""}>Images</span>
              )}
            </NavLink>
          </li>
          <li className="p-2">
          <NavLink
              to="/upload"
              className="text-sm font-semibold leading-6 text-gray-900"
            >
              {({ isActive }) => (
                <span className={isActive ? "text-yellow-500" : ""}>Upload</span>
              )}
            </NavLink>
          </li>
        </ul>
      </nav>
      <div className="max-w-screen-xl mx-auto">
        <Outlet />
      </div>
    </div>
  );
}

function NoMatch() {
  return (
    <div>
      <h2>Nothing to see here!</h2>
      <p>
        <Link to="/">Go to the home page</Link>
      </p>
    </div>
  );
}

export default App;
