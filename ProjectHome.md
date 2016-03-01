A Skeleton Framework is a lightweight, modular PHP5 framework that has:
  * Registry centric design: the Registry is injected everywhere and includes a Loader and DI,
  * Front Controller dispatching Action Controllers with modules,
  * Action Controllers do not need to inherit any base class,
  * Components implemented in layers so you can choose lightweight or more featured implementations,
  * Components that can be use stand-alone.
  * Uses autoloading throughout
It has a standard Front/Action Controller architecture and many useful components. A Registry object is used as framework glue which allows the developer to choose amount of functionality to use. This makes the architecture style customizable to anything from Transaction Scripts to Model/Presentation to various interpretations of MVC. Support for mod\_rewrite and clean URLs, Dependency Injection, pagination, etc. is included.

You may also visit [A Skeleton Framework website](http://www.skeletonframework.com) for additional information and documentation.