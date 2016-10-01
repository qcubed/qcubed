<?php

require_once('../qcubed.inc.php');
require('../includes/header.inc.php');
?>
<div id="instructions" class="full">
    <h1>Dependency Injection Container</h1>

    <p>
        According to Fabien Potencier, the author of Symfony, a Dependency Injection Container is an object that knows how to instantiate and configure objects. And to be able to do its job, it needs to knows about the constructor arguments and the relationships between the objects.
    </p>
    <p>
        <h3>Related Links</h3>
        <a href="http://fabien.potencier.org/do-you-need-a-dependency-injection-container.html">
            Fabien Potencier - series of articles on Dependency Injection
        </a><br />
        <a href="https://www.martinfowler.com/articles/injection.html">
            Martin Fowler - Inversion of Control Containers and the Dependency Injection pattern
        </a>
    </p>
    <p>
        QCubed adds a simple <i>includes/container/AbstractContainer.php<i>, which needs to be extended and the services need to be added manually to the <b>AbstractContainer</b> implementation.<br />
        An example implementation:
    </p>
    <pre>
        <code class="php">
        class Container extends AbstractContainer
        {
            /** @var array $services */
            protected $services = array(
                'logger' => null,
                'repository.user' => null,
                'manager.registration' => null,
            );

            /**
             * @param string  $id
             * @param boolean $newInstance
             *
             * @return Logger
             */
            protected function getLoggerService($id, $newInstance)
            {
                if ($newInstance || !$this->services[$id]) {
                    $this->services[$id] = new Logger();
                }

                return $this->services[$id];
            }

            /**
             * @param string  $id
             * @param boolean $newInstance
             *
             * @return UserRepository
             */
            protected function getRepository_UserService($id, $newInstance)
            {
                if ($newInstance || !$this->services[$id]) {
                    $this->services[$id] = new UserRepository();
                }

                return $this->services[$id];
            }

            /**
             * @param string  $id
             * @param boolean $newInstance
             *
             * @return RegistrationManager
             */
            protected function getLoggerService($id, $newInstance)
            {
                if ($newInstance || !$this->services[$id]) {
                    $this->services[$id] = new RegistrationManager(
                        $this->getService('repository.user', array(), $newInstance),
                        $this->getService('logger', array(), $newInstance)
                    );
                }

                return $this->services[$id];
            }
        }
        </code>
    </pre>
    <p>Usage Example:</p>
    <pre>
        <code class="php">
        class RegisterForm extends QForm
        {
            protected $btnRegister;

            protected function Form_Create()
            {
                $this->btnRegister = new QButton($this);
                $this->btnRegister->AddAction(new QClickEvent(), new QServerAction('btnRegister_Click'));
            }

            protected function btnRegister_Click()
            {
                $this->container->getService('manager.registration')->register();
            }
        }

        $container = new Container();
        RegisterForm::Run('RegisterForm', 'templates/register.tpl', null, $container);
        </code>
    </pre>
    <p>
    If a new dependency is added to the <i>RegisterManager</i> class, it needs to be added only in one place, in the Container.
    </p>
</div>

<?php $mainPage = true; require('../includes/footer.inc.php'); ?>
